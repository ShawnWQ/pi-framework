<?hh

namespace Pi\Odm;
use Pi\Odm\MongoConnection;
use Pi\Odm\MongoClient;
use Pi\Odm\MongoDB\DBCollection;
use Pi\Odm\MongoDB\Cursor;
use Pi\Interfaces\IDatabaseConnection;
use Pi\EventManager;
use Pi\Odm\UnitWork;

class MongoDatabase
  implements IDatabaseConnection{

  public function __construct(protected \MongoDB $mongoDatabase, protected EventManager $eventManager)
  {

  }

  public function selectCollection(string $collectionName)
  {
    // Event preSelectCollection

    $result = $this->doSelectCollection($collectionName);
    return new DBCollection($this, $result, $this->eventManager);
  }

  public function doSelectCollection(string $collectionName)
  {
    return $this->mongoDatabase->selectCollection($collectionName);
  }

  public function authenticate($username, $password) {

  }

  public function getClient()
  {
      $mongoClient = new \MongoClient();
      return $mongoClient;
      $client = new MongoClient($this);
      return $client;
  }
}
