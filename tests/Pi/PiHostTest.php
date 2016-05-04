<?hh

use Pi\HostConfig;
use Mocks\BibleHost;
use Mocks\MockPlugin;
use Mocks\MockEntity;
use Mocks\MockEntityValidator;
use Pi\Host\HostProvider;
use Pi\IContainer;
use Pi\Container;
use Pi\Cache\InMemoryCacheProvider;
use Pi\Interfaces\ICacheProvider,
    Pi\Interfaces\AppSettingsInterface;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\Validation\AbstractValidator;
use Pi\Validation\InlineValidator;

class PiHostTest extends \PHPUnit_Framework_TestCase{

  protected $host;

  public function setUp()
  {
    $this->host = new BibleHost();
    $_SERVER['REQUEST_URI'] = '/test';
  }

  public function tearDown()
  {
    $this->host->dispose();
  }

  public function testCanConfigureAndBuildApplication()
  {
    // configuration is done with BibleHost->configure implementation
    $this->host->init();
    //$this->assertTrue($this->host->)
  }

  /**
   * @slowThreshold 5
   */
  public function testCreatesContainer()
  {
    $this->host->init();
    $this->assertNotNull($this->host->container());
  }

  /**
   * @slowThreshold 5
   */
  public function testAssertHostProviderInstanceIsSet()
  {
    $this->host->init();
    $this->assertTrue($this->host === HostProvider::instance());
  }

  /**
   * @slowThreshold 5
   */
  public function testSetAndGetCacheProvider()
  {
    $this->host->init();
    $this->host->registerCacheProvider(InMemoryCacheProvider::class);
    $provider = $this->host->cacheProvider();
    $this->assertNotNull($provider);
    $this->assertTrue($provider instanceof InMemoryCacheProvider);
  }

  /**
   * @slowThreshold 5
   */
  public function testMessageFactoryIsSet()
  {
    $this->host->init();
    $messageFactory = $this->host->tryResolve('IMessageFactory');
    $this->assertNotNull($messageFactory);
    $producer = $messageFactory->createMessageProducer();
    $this->assertNotNull($producer);
  }

  /**
   * @slowThreshold 5
   */
  public function testExecuteRequestAndWriteResponse()
  {
    $response = $this->host->init();
  }

  /**
   * @slowThreshold 5
   */
  public function testEventManagerIsCreated()
  {
    $this->assertInstanceOf('Pi\EventManager', $this->host->eventManager());
  }

  /**
   * @slowThreshold 5
   */
  public function testCanRegisterPlugin()
  {
    $this->host->registerPlugin(new MockPlugin());
    $this->host->init();
    $loaded = $this->host->getPluginsLoaded();
    $this->assertTrue(in_array(MockPlugin::class, $loaded));
  }

  /**
   * @slowThreshold 5
   */
  public function testAppSettingsIsRegistered()
  {
    $this->host->init();
    $provider = $this->host->container()->get(AppSettingsInterface::class);
    $this->assertTrue($provider != null && $provider instanceof AppSettingsInterface);
  }

  public function notimplementedRegisterAbstractValidator()
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
