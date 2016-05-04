<?hh

use Pi\NotImplementedException,
    Pi\Route,
    Pi\HostConfig,
    Pi\Extensions,
    Pi\Service,
    Pi\Host\ServiceController,
    Pi\Host\Operation,
    Pi\Host\ServiceMeta,
    Pi\Host\OperationMetaFactory,
    Pi\Host\OperationDriver,
    Pi\Cache\LocalCacheProvider,
    Mocks\BibleTestService,
    Mocks\HttpRequestMock,
    Mocks\VerseCreateRequest,
    Mocks\VerseCreateResponse,
    Mocks\TestHost,
    Mocks\MockEnvironment,
    Mocks\MockHostProvider;

class OperationDriverTestS1 extends Service {

  <<Request,Route('/get')>>
  public function get(OperationDriverTestR1 $request)
  {

  }

  <<Request,Route('/get'),Method('PUT')>>
  public function save(OperationDriverTestR1 $request)
  {

  }

  <<Request,Route('/get'),Method('POST')>>
  public function create(OperationDriverTestR1 $request)
  {

  }
}
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

  protected $host;

  public function setUp()
  {
    $this->host = new \Mocks\BibleHost();
    $this->host->init();
    $em = MockHostProvider::instance()->container()->get('EventManager');
    $this->driver = OperationDriver::create(
      array('../'), 
      $this->host->container()->get('EventManager'), 
      $this->host->cacheProvider()
    );

  }

  public function tearDown()
  {
    $this->host->dispose();
  }

  public function testCanLoadServiceMeta()
  {
    $operation = new ServiceMeta(OperationDriverTestS1::class);
    $this->assertEquals(0, count($operation->mappings()));
    $this->driver->loadMetadataForClass(get_class($operation), $operation);
    $this->assertEquals(1, count($operation->mappings()));
  }

  public function testCanLoadOperationMeta()
  {
    $operation = new Operation(OperationDriverTestR1::class);
    $this->assertEquals(0, count($operation->mappings()));
    $this->driver->loadMetadataForOperation(get_class($operation), $operation);
    $this->assertEquals(1, count($operation->mappings())); 
  }
}
