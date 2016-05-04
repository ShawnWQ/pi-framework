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
use Mocks\BibleHost;
use Pi\HostConfig;

class ServiceControllerTest extends \PHPUnit_Framework_TestCase {

  public function setUp()
  {
    MockEnvironment::mock();
  }

  public function testCanInjectRequestContextInService()
  {
    $host = new BibleHost(new HostConfig()); 
    $host->init();
    $service = $host->container()->get('Mocks\BibleTestService');
    $dto = new VerseCreateRequest();
    $dto->setBook('John');
    $dto->setChapter(12);
    $dto->setNumber(4);
    $dto->setText('the verse');
    $context = new HttpRequestMock($dto);
    
    $c = $host->serviceController();
    $c::injectRequestContext($service, $context);
    $this->assertEquals($service->request(), $context);
    $host->dispose();
  }

  public function testServiceControllerasd()
  {
    $host = new BibleHost(new HostConfig()); 
    $mockedRoutes = array();
    $route = new Route('/api/test', 'Mocks\BibleTestService', 'post', false);
    $mockedRoutes[] = $route;
    $host->cacheProvider()->set('ServiceController', json_encode($mockedRoutes));

    $routes = json_decode($host->cacheProvider()->get('ServiceController'));
    $this->assertTrue(is_array($routes));
    $host->dispose();
  }

  public function testServicesAreRegisteredBeforeBuild()
  {
    $_SERVER['REQUEST_URI'] = '/test';

    $host = new BibleHost(new HostConfig());
    $host->init();

    $controller = $host->serviceController();
    $map = $controller->servicesMap();

    $this->assertTrue(is_array($map));
    //$this->assertTrue(count($map) == 1);
    $this->assertArrayHasKey('Mocks\BibleTestService', $map);
    $host->dispose();
  }

  public function testServicesExecutorFnAreRegisteredOnInit()
  {
    $host = $this->createAppHost();
    $controller = $host->serviceController();
    $host->init();

    $messageFactory = $host->tryResolve('IMessageFactory');
    $this->assertNotNull($messageFactory);
    $called = false;
    $producer = $messageFactory->createMessageProducer();
    $subscriber = $messageFactory->createMessageQueueClient();
    $req = new VerseCreateRequest();

    $service = $host->tryResolve('IMessageService');
    $service->registerHandler($req);

    $service->start();

    $service->subscribe(get_class($req), function($r) use(&$called){

      $called = true;

    });
    $this->assertFalse($called);
    $response = $service->publish($req);



    $this->assertTrue($called);
    $host->dispose();
  }

  public function testCanExecuteRequest()
  {
    $host = $this->createAppHost();
    $host->init();
    $host->registerService(new BibleTestService());

    $req = new VerseCreateRequest();
    $context = new HttpRequestMock($req);
    $response = $host->serviceController()->execute($req, $context);
    $this->assertNotNull($response);
    $this->assertInstanceOf('\Mocks\VerseCreateResponse', $response);
    $host->dispose();
  }

  public function testCanGetOperationMetadata()
  {
    $op = new VerseCreateRequest();
    $host = $this->createAppHost();
    $host->init();
    $meta = $host->serviceController()->getClassMetadata(get_class($op));
    $this->assertTrue(array_key_exists('book', $meta->mappings()));
    $this->assertEquals($meta->mappings()['book']->getFieldName(), 'book');
    $host->dispose();
  }

  private function createAppHost()
  {
    return new BibleHost(new HostConfig());
  }
}
