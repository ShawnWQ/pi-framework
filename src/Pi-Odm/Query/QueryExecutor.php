<?hh

namespace Pi\Odm\Query;

use Pi\MongoDBException,
    Pi\Odm\MongoDB\DBCollection,
    Pi\Odm\Query\QueryType,
    Pi\Odm\Query\Query,
    Pi\Odm\MongoDB\Cursor,
    Pi\Odm\Mapping\EntityMetaData,
    Pi\Odm\MongoManager,
    Pi\Odm\UnitWork;

class QueryExecutor {

  /**
   * The iterator for the current operation
   * @var [type]
   */
  protected $iterator;

  protected $type = 'find';

  private $unitOfWorkHints = array();

  public function __construct(
    protected UnitWork $unitWork,
    protected MongoManager $mongoManager,
    protected EntityMetaData $class,
    protected DBCollection $collection,
    protected array $query = array(),
    protected array $options = array(),
    protected $hydrate = true,
    protected $refresh = false,
    protected array $primers = array(),
    $requireIndexes = null)
  {
    if(isset($this->query['type'])) {
    $this->type = $this->query['type'];
    }

  }

  /**
   * Helper to execute the query and return the result which is a iterator
   */
  public function getIterator()
  {
    switch($this->type) {
      case QueryType::TYPE_FIND:
      case QueryType::TYPE_GROUP:
      case QueryType::TYPE_MAP_REDUCE:
      case QueryType::TYPE_DISTINCT:
      case QueryType::TYPE_GEO_NEAR:
      case QueryType::TYPE_GEO_NEAR:
      case QueryType::TYPE_FIND_AND_UPDATE:
        break;

      default:
        throw new \Exception('Cant return a iterator for query type ' . $this->type);
    }


    if($this->iterator === null){
        $iterator = $this->doExecute();

        // validate if iterator is a Iterator

      $this->iterator = $iterator;
    }

    return $this->iterator;
  }

  public function getQuery()
  {
    return $this->query;
  }

  public function getSingleResult()
  {
    if($this->hydrate && $this->type === QueryType::TYPE_FIND_AND_UPDATE) {
      $value = $this->getIterator();
      if(is_null($value)) {
        return null;
      }
      return $this->getDocument($value);

    } else if($this->hydrate) {
        $value = $this->getIterator()->getSingleResult();
        if(!$value) {
          return null;
        }
        return $this->getDocument($value);
    }


  }

  public function type()
  {
    return $this->type;
  }

  public function interate()
  {
    return $this->iterator;
  }

  public function toArray()
  {
    $iterator = $this->getIterator();
    return array_values($iterator->toArray());
  }

  public function count ()
  {
      $query = $this->prepareQuery();
      return $this->collection->count($query);
  }


  public function assertExecute()
  {
    $res = $this->execute();

    if(intval($res['ok']) !== 1) {
      MongoDBException::notUpdated($this->query);
    }
    switch ($this->type) {
      case QueryType::TYPE_UPDATE:
        if(!is_array($res) || !array_key_exists('updatedExisting', $res) || intval($res['updatedExisting']) !== 1) {
          MongoDBException::notUpdated($this->query);
        }
        break;
      
      default:
        
        break;
    }

    return $res;
  }

  public function execute()
  {
    $results = $this->doExecute();

    if(!$this->hydrate || $results === null){

      return $results;
    }

    // findAndModify if has identifier field, is fineandupdate or remove
    // try to hydrate
    //if($this->type
    // find

    switch($this->type){

      case QueryType::TYPE_FIND:
          $docs = array();
          foreach($results as $key => $value){
            $docs[$key] = $this->getDocument($value);
          }
            return array_values($docs);
          break;


          case QueryType::TYPE_UPDATE:
          break;

          case QueryType::TYPE_FIND_AND_UPDATE:

          break;

          default:

          break;
    }
    return $this;
  }

    protected function getDocument($value)
    {

        return $this->unitWork->getDocument($this->class->name, $value, $this->unitOfWorkHints);
    }

    protected function prepareQuery()
    {
        $query = array();
        $ops = array('$eq', '$pull');


        foreach($ops as $op){
          if(array_key_exists($op, $this->query['query']) && is_array($this->query['query'][$op])) {
            foreach($this->query['query'][$op] as $k => $v) {
                $query[$k] = $v;
              }
          }
        }

        if(array_key_exists('$in', $this->query['query']) && is_array($this->query['query']['$in'])) {
            foreach($this->query['query']['$in'] as $k => $v) {
                $query[$k]['$in'] = $v;

              }

          }
        if(array_key_exists('$or', $this->query['query']) && is_array($this->query['query']['$or'])) {
            foreach($this->query['query']['$or'] as $k => $v) {

              foreach($v  as $c => $n) {
                $query['$or'][] = array($c =>  $n);
              }

            }

          }

        return $query;
    }

    public function doExecute()
    {
        $query = $this->prepareQuery();

        switch($this->type){
          case QueryType::TYPE_FIND:
              // Prepare
              if(isset($query['$or'])) {

              }
            $cursor = $this->collection->find(
              $query,
              isset($this->query['select']) ? $this->query['select'] : array()
            );
            if($cursor === null) {
              return;
            }
            return $this->handleCursor($cursor);
          break;

          case QueryType::TYPE_UPDATE:
            $result = $this->collection->update($query, $this->query['newObj'], $this->options);
            return $this->handleUpdateResult($result);
          break;

          case QueryType::TYPE_FIND_AND_UPDATE:
            $fields =   isset($this->query['select']) ? $this->query['select'] : array();
            $cursor = $this->collection->findAndUpdate($query, $this->query['newObj'], $fields);
            return $cursor;
          break;

          case QueryType::TYPE_REMOVE:
            $result = $this->collection->remove($query, $this->query['newObj'], $this->options);
            return $this->handleRemoveResult($result);
          break;

          default:

          break;
    }
  }

private function handleUpdateResult($result)
{
    return $result;
}

private function handleRemoveResult($result)
{
    return $result;
}
  /**
    * Returns an array containing the specified keys and their values from the
    * query array, provided they exist and are not null.
    *
    * @param string $key,... One or more option keys to be read
    * @return array
    */
   private function getQueryOptions(/* $key, ... */)
   {
       $opts = array_filter(
           array_intersect_key($this->query, array_flip(func_get_args())),
           function($value) { return $value !== null; }
       );

       return $opts;
   }
  protected function handleCursor(Cursor $cursor)
  {

      // implement immportal
    foreach ($this->getQueryOptions('hint', 'limit', 'skip', 'slaveOkay', 'sort') as $key => $value) {
          $cursor->$key($value);
      }
      if ( ! empty($this->query['snapshot'])) {
          $cursor->snapshot();
      }
      return $cursor;
  }

  private function prepareIterator()
  {

  }
}
