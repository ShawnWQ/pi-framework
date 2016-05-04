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
use Pi\Host\ServiceAttributeDriver;

class ODReq {}
class ODRes {}

class ODService {

  <<Route('/test'),Method('POST')>>
  public function get(ODReq $req) {}
}
class OperationDriverTest extends \PHPUnit_Framework_TestCase {

  protected $driver;

  public function setUp()
  {
    $host = new \Mocks\BibleHost();
    $host->init();
    $em = MockHostProvider::instance()->container()->get('EventManager');
    $d = new ServiceAttributeDriver();
    $this->driver = $d::create(array('../'));
  }

  public function testCanLoadMetadata()
  {

  }
}
