<?hh

use Pi\HostConfig;
use Mocks\BibleHost;
use Mocks\MockPlugin;
use Mocks\MockEntity;
use Mocks\MockEntityValidator;
use Pi\Host\HostProvider;
use Pi\IContainer;
use Pi\Container;
use Pi\Cache\LocalCacheProvider;
use Pi\Interfaces\ICacheProvider,
    Pi\Interfaces\AppSettingsInterface;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\Validation\AbstractValidator;
use Pi\Validation\InlineValidator;

class PiHostTest
  extends \PHPUnit_Framework_TestCase{

  protected $host;

  public function setUp()
  {
    $this->host = new BibleHost();
    $_SERVER['REQUEST_URI'] = '/test';
  }

  public function testAssertHostProviderInstanceIsSet()
  {
    $this->host->init();
    $this->assertTrue($this->host === HostProvider::instance());
  }

  public function testCreatesContainer()
  {
    $this->host->init();
    $this->assertNotNull($this->host->container());
  }

  public function testEndRequestAndOutputResponse()
  {
    //$this->assertFalse($this->host->container->get('IResponse')->wasOutputed());
    $this->host->init();
    $this->assertTrue($this->host->container->get('IResponse')->isClosed());
  }

  public function testSetAndGetCacheProvider()
  {
    $this->host->init();
    $this->host->registerCacheProvider(new LocalCacheProvider('/tmp/pi-cache.txt'));
    $provider = $this->host->cacheProvider();
    $this->assertNotNull($provider);
    $this->assertTrue($provider instanceof LocalCacheProvider);
  }

  public function testMessageFactoryIsSet()
  {
    $this->host->init();
    $messageFactory = $this->host->tryResolve('IMessageFactory');
    $this->assertNotNull($messageFactory);
    $producer = $messageFactory->createMessageProducer();
    $this->assertNotNull($producer);
  }

  public function testExecuteRequestAndWriteResponse()
  {
    $response = $this->host->init();
  }

  public function testEventManagerIsCreated()
  {
    $this->assertInstanceOf('Pi\EventManager', $this->host->eventManager());
  }

  public function testCanRegisterPlugin()
  {
    $this->host->registerPlugin(new MockPlugin());
    $this->host->init();
    $loaded = $this->host->getPluginsLoaded();
    $this->assertTrue(in_array(get_class(new MockPlugin()), $loaded));
  }

  public function testRegisterPreInitRequestFilters()
  {
    $a = false;
    $this->host->preRequestFilters()->add(function(IRequest $req, IResponse $res) use(&$a){
      $a = true;
    });
    $this->assertFalse($a);
    $this->host->init();
    $this->assertTrue($a);
  }

  public function testRegisterPostInitResponseFilters()
  {
   $a = false;
    $this->host->postRequestFilters()->add(function(IRequest $req, IResponse $res) use(&$a){
      $a = true;
    });
    $this->assertFalse($a);
    $this->host->init();
    $this->assertTrue($a);
  }

  public function testAppSettingsIsRegistered()
  {
    $this->host->init();
    $provider = $this->host->container()->get('AppSettingsInterface');
    $this->assertTrue($provider != null && $provider instanceof AppSettingsInterface);
  }

  public function testRegisterAbstractValidator()
  {
    $validator = new MockEntityValidator();
    $entity = new MockEntity();
    $res = $validator->validate($entity);
    $this->host->init();
    $validator = $this->host->getValidator($entity);
    $this->assertTrue($validator instanceof AbstractValidator);

    $response = $validator->validate($entity);
    $this->assertFalse($response->isValid());
  }
}
