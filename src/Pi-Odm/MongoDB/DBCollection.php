<?hh

namespace Pi\Odm\MongoDB;

use Pi\Odm\MongoDatabase;
use Pi\EventManager;
use Pi\Odm\UnitWork;

class DBCollection {

    public function __construct(protected MongoDatabase $database, protected \MongoCollection $collection, protected EventManager $eventManager, protected $numRetries = 1)
    {

    }

    public function getMongoCollection() : \MongoCollection
    {
      return $this->collection;
    }

    public function deleteIndexes()
    {
      return $this->collection->deleteIndexes();
    }

    public function deleteIndex($key)
    {
      return $this->collection->deleteIndex($key);
    }

    public function update($query, $newObj, array $options = array())
    {

      return $this->doUpdate($query, $newObj, $options);
    }

    protected function doUpdate($query, $newObj, $options = array())
    {
      $result = $this->collection->update($query, $newObj, $options);
      return $result;
    }

    public function remove($query, array $options = array())
    {
      return $this->doRemove($query, $options);
    }

    protected function doRemove($query, array $options)
    {
      return $this->collection->remove($query);
    }


    public function insert(array &$a, array $options = array())
    {
      // Event preInsert
      $result = $this->doInsert($a, $options);
      return $result;
    }

    public function batchInsert(array &$a, array $options = array())
    {
      //preBatchInsert
      $result = $this->doBatchInsert($a, $options);
      //postBatchInsert
      return $result;
    }

    public function doBatchInsert(&$a, $options = array())
    {
      $result = $this->collection->batchInsert($a, $options);
      return $result;
    }

    public function doInsert(array &$a, array $options)
    {
      $document = $a;
      $result = $this->collection->insert($document, $options);

      if(isset($document['id'])){
        $a['id'] = $document['_id'];
      }

      return $result;
    }

    public function findAndUpdate($query, $update, array $fields = array(), $sort = null, $upsert = true)
    {
      $cursor = $this->retry(function() use($query, $update, $sort, $upsert, $fields){
        return $this->collection->findAndModify($query, $update);
      });

      return $cursor;
    }

    public function find(array $query, array $fields = array())
    {
      return $this->doFind($query, $fields);
    }

    public function doFind(array $query, array $fields = array())
    {
      $cursor = $this->retry(function() use($query, $fields){
        $mongoCursor = $this->collection->find($query, $fields);
        //$class = $this->unitWork->documentManager->getClassMetadataForCollection($this->collection->name);
        $cursor = new CursorEx($this, $mongoCursor, $query, $fields);
        return $cursor;
      });

        return $cursor;
    }

    public function findOneById($id, array $fields = array())
    {

      return $this->doFindOne(array('_id' =>  $id), $fields);
    }

    public function doFindOne(array $query, array $fields)
    {
      return $this->retry(function() use($query, $fields){

        $result = $this->collection->find($query, $fields);

        $cursor = new CursorEx($this, $result, $query, $fields);
        return $cursor->getSingleResult();
      });
    }

    public function retry((function() : mixed) $fn)
    {
      if($this->numRetries < 1){
        return $fn();
      }

      $firstEx = null;
      for($i = 0; $i <= $this->numRetries; $i++){
        try  {
          return $fn();
        }
        catch(\Exception $ex){
          if(is_null($firstEx)){
            $firstEx = $ex;
          }
          if($i === $this->numRetries){
            throw $firstEx;
          }
        }
      }

    }

    public function count(array $query = array(), $limit = 0, $skip = 0)
    {
      $collection = $this->collection;
      $fn = function() use ($collection, $query, $limit, $skip) {
          return $collection->count($query, $limit, $skip);
      };
      return $this->retry($fn);

    }

    public function aggregate(array $pipeline, array $options = array())
    {
      // Event pre agregate
      $result = $this->doAggregate($pipeline, $options);

      // post aggregate $result = $eventArgs->getData();

      return $result;
    }

    public function doAggregate(array $pipeline, array $options)
    {

    }
}
