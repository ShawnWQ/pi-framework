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
use Pi\Host\OperationMetaFactory;
use Pi\Host\OperationDriver;

class OperationMetaFactoryTest extends \PHPUnit_Framework_TestCase {

  public function setUp()
  {
    $host = new \Mocks\BibleHost();
    $host->init();
    $em = MockHostProvider::instance()->container()->get('EventManager');
    $driver = OperationDriver::create(array('../'), $em, $host->cacheProvider());
    $factory = new OperationMetaFactory($em, $driver);
  }

  public function testCanRegisterOperations()
  {

  }
}
