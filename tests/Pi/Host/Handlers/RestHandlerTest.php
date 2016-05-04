<?hh
use Mocks\OdmContainer;
use Pi\Host\Handlers\RestHandler;
use Mocks\BibleHost;

class RestHandlerTest extends \PHPUnit_Framework_TestCase {

  protected $host;

  public function setUp()
  {
    $this->host = new BibleHost();
  }

  public function testCanBindRouteParameters()
  {
    $_SERVER['REQUEST_URI'] = '/verse/5';
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $res = $this->host->init();
  }
}
