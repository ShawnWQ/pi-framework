<?hh
use Pi\Odm\MongoConnection;
use Pi\Odm\MongoDatabase;
use Mocks\OdmContainer;
use Pi\Odm\MongoClient;
use Pi\Odm\MongoDB\DBCollection;

class MongoDatabaseTest
  extends \PHPUnit_Framework_TestCase {

  protected $container;

  public function setUp()
  {
    $this->container = OdmContainer::get();
  }

  public function testCanGetCollection()
  {
    $db = $this->createDatabase();
    $collection = $db->selectCollection('test-col');
    $this->assertTrue($collection instanceof DBCollection);
  }

  public function testCanGetClient()
  {
    $db = $this->createDatabase();
    $client = $db->getClient();
    $this->assertTrue($client instanceof \MongoClient);
  }

  private function createDatabase()
  {
    $con = $this->container->get('IDbConnection');
    $mongoDB = $con->getMongoClient()->selectDB('random-db');
    return new MongoDatabase($mongoDB, $this->container->get('EventManager'));
  }
}
