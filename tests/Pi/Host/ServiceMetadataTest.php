<?hh

use Pi\EventManager,
    Pi\Route,
    Pi\Host\ServiceMetadata,
    Pi\Host\BasicRequest,
    Pi\Host\BasicResponse,
    Pi\Host\OperationDriver,
    Pi\Host\Operation,
    Pi\Interfaces\IService,
    Mocks\BibleHost,
    Mocks\BibleTestService,
    Mocks\VerseCreateRequest,
    Mocks\VerseCreateResponse,
    Mocks\MockHostProvider,;




class ServiceMetadataTest extends \PHPUnit_Framework_TestCase {

  public function setUp()
  {
    $this->host = new BibleHost();
    $this->host->init();
  }

  public function tearDown()
  {
    $this->host->dispose();
  }

  public function testWhenBuildAllOperationsAndMapsAreCached()
  {
    $this->host->build();
    $operation = $this->host->metadata()->loadFromCached(VerseCreateRequest::class);
    $this->assertFalse(is_null($operation));
    $this->assertTrue($operation instanceof Operation);
  }

  public function testCanLoadOperationsFromMemoryAndCache()
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
    $em = $this->host->container()->get('EventManager');
    
    $driver = OperationDriver::create(array(), $em, $this->host->cacheProvider());
    $serviceMeta = new ServiceMetadata(
      $this->host->routes, 
      $em, 
      $driver, 
      $this->host->cacheProvider(), 
      $this->host->logFactory->getLogger(ServiceMetadata::class)
    );
    $this->assertNotNull($serviceMeta);
    $serviceMeta->add(get_class($svc), get_class($dto), get_class($res));
    $this->assertTrue($serviceMeta->getServiceTypeByRequest(get_class($dto)) === get_class($svc));
  }

  public function testCanHydrateAndCacheOperation()
  {
    $this->addDefaultMetadata();
    $cached = unserialize($this->host->cacheProvider()->get(ServiceMetadata::CACHE_METADATA_KEY . VerseCreateRequest::class));
    
    $operation = $this->host->metadata()->getOperation(VerseCreateRequest::class);

    $this->assertTrue($operation instanceof Operation);
    $metadata = $this->host->metadata()->getOperationMetadata(VerseCreateRequest::class);

    $this->assertTrue($operation instanceof Operation);
  }

  public function testCanGetServiceTypeByOperation()
  {
    $this->addDefaultMetadata();
    $serviceType = $this->host->metadata()->getServiceTypeByRequest(VerseCreateRequest::class);
    $this->assertEquals(BibleTestService::class, $serviceType);
  }

  public function testCanGetResponseTypeByRequest()
  {
    $this->addDefaultMetadata();
    $responseType = $this->host->metadata()->getResponseTypeByRequest(VerseCreateRequest::class);
    $this->assertEquals(VerseCreateResponse::class, $responseType);
  }

  /*
   * ServiceMetadata constructs without any operation
   * Routes and operation map is cached
   * Operations are created per request, cached
   */

  protected function addDefaultMetadata()
  {
    $this->host->metadata()->add(BibleTestService::class, VerseCreateRequest::class, VerseCreateResponse::class);
  }
}
