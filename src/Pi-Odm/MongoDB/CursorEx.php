<?hh

namespace Pi\Odm\MongoDB;
use Pi\Odm\MongoDatabase;
use Pi\EventManager;
use Pi\Odm\Mapping\EntityMetaData;
use Pi\Odm\UnitWork;

/**
 * http://php.net/manual/en/class.mongocursor.php
 */
class CursorEx extends Cursor {

  public function __construct(protected DBCollection $collection, protected \MongoCursor $mongoCursor, protected array $query = array(), protected array $fields = array(), protected $retries = 0)
  {
    parent::__construct($collection, $mongoCursor, $query, $fields, $retries);
  }

  protected $unitOfWorkHints;

  /**
   * @see http://php.net/manual/en/mongocursor.dead.php
   * @return [type] [description]
   */

  public function count($fouldOnly = false)
  {
    $cursor = parent::count($fouldOnly);
    return $cursor;
  }

  public function sort($fields)
  {
    parent::sort($fields);
  }
  public function current ()
  {
    $current = parent::current();
    /*
    if($current instanceof \MongoGridFSFile) {
      throw new \Exception('GridFs cursor->current not implemented yet');
    }

    if($current !== null && $this->hydrate){
      $current = $this->unitWork->getDocument($this->class->name, $current, $this->unitOfWorkHints);
    }
     */
    return $current;
  }

  public function next()
  {
    $next = parent::next();

    return $next;
  }


  public function recreate()
  {
      return parent::recreate();
  }

public function first()
{
    return parent::first();
}

  public function reset()
  {
    parent::reset();
  }

  public function toArray($useKeys = true)
  {
    $cursor = parent::toArray();
    return $cursor;
  }

  public function getSingleResult()
  {
      $result = parent::getSingleResult();
      return $result;
  }
}
