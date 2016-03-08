<?hh
use Mocks\BibleHost;
use Pi\EventManager;
use Pi\Host\ServiceMetadata;
use Pi\Host\BasicRequest;
use Pi\Host\BasicResponse;
use Pi\Interfaces\IService;
use Mocks\BibleTestService;
use Mocks\VerseCreateRequest;
use Mocks\VerseCreateResponse;
use Pi\Route;
use Mocks\MockHostProvider;
use Pi\Host\OperationDriver;

class ServiceMetadataTest extends \PHPUnit_Framework_TestCase {

  public function setUp()
  {

  }

  public function testCreationServiceMetadata()
  {
    $dto = new VerseCreateRequest();
    $req = new BasicRequest($dto);
    $req->setDto($dto);

    $res = new VerseCreateResponse();

    $svc = new BibleTestService();
    $routes = Map{};

    $route = new Route('/test', 'Service', 'Request');
    $host = new BibleHost();
    $host->container();
    $em = $host->container()->get('EventManager');
    
    $driver = OperationDriver::create(array(), $em, $host->cacheProvider());
    $serviceMeta = new ServiceMetadata($routes, $em, $driver, $host->cacheProvider());
    $this->assertNotNull($serviceMeta);
    $serviceMeta->add($svc, $dto, $res);

    $this->assertTrue($serviceMeta->getServiceTypeByRequest($dto) instanceof IService);
  }
}
