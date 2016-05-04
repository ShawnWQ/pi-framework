<?hh

namespace Pi\Odm\MongoDB;
use Pi\Odm\MongoDatabase;
use Pi\EventManager;
use Pi\Odm\Mapping\EntityMetaData;
use Pi\Odm\UnitWork;

/**
 * http://php.net/manual/en/class.mongocursor.php
 */
class Cursor implements Iterator<TMongoCursor> {

  protected $hint;

  protected $limit = 40;

  protected $skip;

  protected $sort;

  protected $snapshot;

  protected $hydrate;

  public function __construct(protected DBCollection $collection, protected \MongoCursor $mongoCursor, protected array $query = array(), protected array $fields = array(), protected $retries = 0)  {

  }

  public function hydrate($hydrate = true)
  {
    $this->hydrate = $hydrate;
    return $this;
  }

  public function mongoCursor()
  {
    return $this->mongoCursor;
  }

  /**
   * @see http://php.net/manual/en/mongocursor.dead.php
   * @return [type] [description]
   */
  public function dead()
  {
    return $this->mongoCursor->dead();
  }

  public function getFields()
  {
    return $this->fields;
  }

  public function fields(array $f)
  {
      $this->fields = $f;
      $this->mongoCursor->fields($f);
      return $this;
  }

  public function count($fouldOnly = false)
  {
    $cursor = $this;

    return $this->retry(function() use($cursor, $fouldOnly){
      return $cursor->mongoCursor()->count($fouldOnly);
    }, true);
  }

  public function valid()
  {
      return $this->mongoCursor->valid();
  }

  public function sort($fields)
  {
      foreach ($fields as $fieldName => $order) {
          if (is_string($order)) {
              $order = strtolower($order) === 'asc' ? 1 : -1;
          }

          if (is_scalar($order)) {
              $fields[$fieldName] = (int)$order;
          }
      }
      $this->sort = $fields;
      $this->mongoCursor->sort($fields);
      return $this;
  }
  public function current ()
  {
    $current = $this->mongoCursor->current();
    return $current;
  }

  public function next()
  {
      $cursor = $this;
      $this->retry(function() use ($cursor) {
          return $cursor->mongoCursor()->next();
      }, false);
  }

  public function first()
  {
    throw new \Exception('not implemented');
  }

  public function limit(int $limit)
  {
    $this->limit = $limit;
    $this->mongoCursor->limit($limit);
    return $this;
  }

  public function skip(int $num)
  {
    $this->skip = $num;
    $this->mongoCursor->skip($num);
    return $this;
  }

  public function key ()
  {
    return $this->mongoCursor->key();
  }

  public function rewind ()
  {
    $cursor = $this;
    $this->retry(function() use ($cursor) {
        return $cursor->mongoCursor()->rewind();
    }, false);
  }

  public function snapshot()
  {
    $this->snapshot = true;
    $this->mongoCursor->snapshot();
    return $this;
  }

  public function recreate()
  {
    $this->mongoCursor = $this->collection->getMongoCollection()->find($this->query, $this->fields);

    if ($this->snapshot) {
        $this->mongoCursor->snapshot();
    }

    if($this->limit !== null){
      $this->mongoCursor->limit($this->limit);
    }

    if($this->skip !== null) {
      $this->mongoCursor->skip($this->skip);
    }

    if($this->sort !== null) {
      $this->mongoCursor->sort($this->sort);
    }
  }

  public function reset()
  {
    $this->mongoCursor->reset();
  }

  public function toArray($useKeys = true)
  {
    $cursor = $this;
      return $this->retry(function() use ($cursor, $useKeys) {
          return iterator_to_array($cursor, $useKeys);
      }, true);
  }

  public function getSingleResult()
  {
      $originalLimit = $this->limit;
      $this->reset();
      $this->limit(1);
      $result = current($this->toArray(false)) ?: null;
      $this->reset();
      $this->limit($originalLimit);
      return $result;
  }

  protected function retry($retryFn, $recreate = false)
  {

    if($this->retries < 1){
      return $retryFn();
    }

    $firstEx = null;

    for($i = 0; $i <= $this->retries; $i++){
      $ex = null;
      try {
        $retryFn();
      } catch(\MongoCursorException $ex){

      } catch(\MongoConnectionException $ex){

      }

      if($firstEx === null){
        $firstEx = $ex;
      }
      // @BUG HHVM $firstEx
      if($i === $this->retries && $firstEx !== null){
        throw $firstEx();
      }
    }
  }
}
