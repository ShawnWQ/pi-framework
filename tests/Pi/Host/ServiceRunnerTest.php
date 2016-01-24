<?hh

use Pi\Host\ServiceRunner;
use Pi\Host\ActionContext;
use Pi\Message\InMemoryFactory;
use Pi\Interfaces\IContainer;
use Pi\HostConfig;
use Mocks\TestHost;
use Mocks\BibleHost;
use Mocks\BibleTestService;
use Mocks\VerseCreateRequest;
use Mocks\HttpRequestMock;
use Pi\Host\PhpResponse;

class ServiceRunnerTest
  extends \PHPUnit_Framework_TestCase {

    protected $host;
    public function setUp()
    {
      $this->host = new BibleHost(new HostConfig());
      $this->host->setMessageFactory(new InMemoryFactory());
    }
    public function testCreateAndAutoWireService()
    {
      $s = new BibleTestService();
      $sType = get_class($s);
      $this->host->serviceController()->registerService($s);
      $context = new ActionContext();
      $context->setRequestType(get_class(new VerseCreateRequest()));
      $context->setServiceType($sType);

      $handler = function(IContainer $container) use($sType){
        $service = new $sType();
        $service->ioc($container);
        return $service;
      };
      $context->setServiceAction($handler);

      $runner = new ServiceRunner($this->host, $context);

      $dto = new VerseCreateRequest();
      $httpReq = new HttpRequestMock($dto);
      $res = new PhpResponse();
      $this->host->init();
      $res = $runner->executeOneWay($httpReq, $res, $dto);

      $this->assertNotNull($res);
    }
  }
