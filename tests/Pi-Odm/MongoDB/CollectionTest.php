<?hh
use Pi\Odm\MongoDB\DBCollection;
use Pi\Odm\MongoDatabase;
use Pi\Odm\UnitWork;
use Pi\EventManager;
use Mocks\OdmContainer;
use Pi\Common\RandomString;

class CollectionTest extends \PHPUnit_Framework_TestCase{

  protected $database;

  protected $eventManager;

  protected $mongoCollection;

  public function setUp()
  {
    $container = OdmContainer::get();
    $this->eventManager = $container->get('EventManager');
    $client = new \MongoClient();
    $db = $client->selectDB('test-db');

    $this->mongoCollection = $db->selectCollection('test');
    $this->database = new MongoDatabase($db, $this->eventManager);
  }

  public function testCanGetCollection()
  {
    $collection = $this->getCollection();
    $this->assertNotNull($collection);
    $this->assertInstanceOf('Pi\Odm\MongoDB\DBCollection', $collection);
  }

  public function testInsertNewDocumentAndGetById()
  {
    $collection = $this->getCollection();

    // ids are not generated by the collection
    $doc = array('name' => 'testName', '_id' => new \MongoId());
    $collection->insert($doc);
    $this->assertTrue(isset($doc['_id']));

    $newDoc = $collection->findOneById($doc['_id']);
    $this->assertEquals($newDoc['name'], $doc['name']);
  }

  public function testUpdate()
  {
    $collection = $this->getCollection();
    $doc = array('name' => 'testName', '_id' => new \MongoId());
    $collection->insert($doc);
    $this->assertTrue(isset($doc['_id']));

    $result = $collection->update(array('_id' => $doc['_id']), array('$set' => array('name' => 'Gui')));
    $this->assertTrue($result['updatedExisting'] === true);
  }

  public function testFind()
  {
    $collection = $this->getCollection();
    $doc = array('name' => RandomString::generate(40), '_id' => new \MongoId());
    $collection->insert($doc);
    $this->assertTrue(isset($doc['_id']));

    $newDoc = $collection->find(array('name' => $doc['name']));
    $this->assertTrue(count($newDoc) == 1);
  }

  public function testFindAndUpdate()
  {
    $col = $this->getCollection();
    $doc = array('counter' => 2, '_id' => new \MongoId());
    $col->insert($doc);

    $r = $col->findAndUpdate(array('_id' => $doc['_id']), array('$inc' => array('counter' => 2)), array('_id', 'counter'));
    
    $doc = $col->findOneById($doc['_id']);
    $this->assertEquals($doc['counter'], 4);
  }


  public function testCanCount()
  {
    $collection = $this->getCollection();
    $count = $collection->count();
    $this->assertTrue(is_int($count));
  }

  private function getCollection()
  {
    return new DBCollection($this->database, $this->mongoCollection, $this->eventManager);
  }
}
