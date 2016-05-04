<?hh

use Pi\Odm\MongoConnection;
use Pi\Logging\LogManager;
use Mocks\MockEntity;
use Mocks\OdmContainer;
use Mocks\BibleHost;

class MongoConnectionTest
  extends \PHPUnit_Framework_TestCase {

  protected $logger;

  protected $appHost;

  public function setUp()
  {
    $this->logger = LogManager::getLogger(get_class($this));
    $this->appHost = OdmContainer::get();
  }

  public function testCanFindDocuments()
  {
    $entity = new MockEntity();
    $entity->name('another');
    $con = $this->appHost->container->get('IDbConnectionFactory')->open();
    $tran = $con->beginTransaction();
    $con->insert($entity);
    $find = $con->select($entity);
    $this->assertTrue(count($find) >= 1);

  }

  public function testCreateDefaultConnectionString()
  {
    $this->logger->info('testCreateDefaultConnectionString');
    $con = $this->createConnection();

    $string = $con->getConnectionString();

    $this->assertEquals($string, 'localhost:6667');

  }

  public function testGetDefaultConnectionTimeout()
  {

    $this->logger->info('testGetDefaultConnectionTimeout');
    $con = $this->createConnection();
    $timeout = $con->getConnectionTimeout();

    $this->assertEquals($timeout, 30);
  }

  public function testCreateDatabaseAndGet()
  {
    $this->logger->info('testCreateDatabaseAndGet');
  $con = $this->createConnection();
    $db = $con->getDatabase();
    $this->assertEquals($db, 'default');
  }

  public function testCanCreateTransaction()
  {
$con = $this->createConnection();    $transaction = $con->beginTransaction();
    $this->assertInstanceOf('\Pi\Odm\Interfaces\IDbTransaction', $transaction);
  }

  private function createConnection()
  {
    $factory = $this->appHost->get('IDbConnectionFactory');

    return $factory->open();

  }

  private function createMongoClient()
  {
    return new \MongoClient();
  }
}
