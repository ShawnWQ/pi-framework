<?hh
use Pi\NotImplementedException;
use Pi\Host\ServiceController;
use Pi\Cache\LocalCacheProvider;
use Pi\Route;
use Mocks\BibleTestService;
use Mocks\HttpRequestMock;
use Mocks\VerseCreateRequest;
use Mocks\VerseCreateResponse;
use Mocks\TestHost;
use Mocks\MockEnvironment;
use Mocks\MockHostProvider;
use Pi\HostConfig;
use Pi\Host\Operation;
use Pi\Host\OperationMetaFactory;
use Pi\Host\OperationDriver;
use Pi\Extensions;

class OperationDriverTestR1 {

  public function __construct(
    protected string $name
    )
  {

  }

  <<String>>
  public function getName() : string
  {
    return $this->name;
  }
}
class OperationDriverTest extends \PHPUnit_Framework_TestCase {

  protected $driver;

  public function setUp()
  {
    $host = new \Mocks\BibleHost();
    $host->init();
    $em = MockHostProvider::instance()->container()->get('EventManager');
    $this->driver = OperationDriver::create(array('../'), $host->container()->get('EventManager'), $host->cacheProvider());
  }

  public function testCanLoadMetadata()
  {
    $entity = new OperationDriverTestR1('test');
    $operation = new Operation(Extensions::getOperationName($entity));
    $this->assertEquals(count($operation->mappings()), 0);
    $this->driver->loadMetadataForClass(get_class($entity), $operation);
    $this->assertEquals(count($operation->mappings()), 1);
  }

  public function testCanCacheRequests()
  {

  }
}
