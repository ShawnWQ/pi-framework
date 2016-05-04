<?hh
use Pi\Service;
use Mocks\VerseCreateRequest;
use Mocks\VerseCreateResponse;
use Mocks\BibleHost;
use Pi\Interfaces\IPiHost;
use Pi\Interfaces\IContainer;
use Pi\HostConfig;
use Pi\Host\HostProvider;

class HostA extends BibleHost {
	public function configure(IContainer $appHost){
		parent::configure($appHost);
		$this->registerService(new ServiceA());
		$this->registerService(new ServiceB());
	}
}
class RequestA {}
class ResponseA {}
class RequestB {}
class ResponseB {}
class RequestS {}
class ResponseS {}
class ServiceA extends Service {

	<<Request,Method('GET'),Route('/test')>>
	public function get(RequestB $request)
	{
		$_SESSION['A'] = 'a';
		$this->request()->getSession();
		$this->request()->getSession();

		return new ResponseB();
	}

	<<Request,Method('GET'),Route('/test-s')>>
	public function session(RequestS $request)
	{


		return new ResponseS();
	}

	<<Request,Method('GET'),Route('/test-2')>>
	public function get2(RequestA $request)
	{
		$_SESSION['A'] = 'a';
		$r = $this->execute(new VerseCreateRequest());
		return $r;
	}
}
class ServiceB extends Service {

	<<Request,Method('GET'),Route('/test-b')>>
	public function get(RequestB $request)
	{
		$_SESSION['A'] = 'ab';
		$this->request()->getSession();
		$this->request()->getSession();

		return new ResponseB();
	}
}

class ServiceTest extends \PHPUnit_Framework_TestCase{

	protected $host;

	public function setUp()
	{
		$_SESSION['A'] = 'b';
		$config = new HostConfig();
    	$config->setConfigsPath($_SERVER["DOCUMENT_ROOT"]  . 'config.json');
		$this->host = new HostA($config);
	}

	public function testServiceCreation()
	{
		$_SERVER['REQUEST_URI'] = '/test';
		$this->host->init();
		HostProvider::execute(new RequestB());
		$this->assertEquals($_SESSION['A'], 'a');
	}

	public function testSession()
	{
		$_SERVER['REQUEST_URI'] = '/test';
		$this->host->init();
		HostProvider::execute(new RequestS());
	}

	public function testServiceCanExecute()
	{
		$_SERVER['REQUEST_URI'] = '/test-2';
		$this->host->init();
		HostProvider::execute(new RequestA());
		$this->assertEquals($_SESSION['A'], 'a');
	}

	public function testSameDtoReusedDiferenceServices()
	{
		$this->setUri('/test-b');
		$this->host->init();
		HostProvider::execute(new RequestB());
		$this->assertEquals($_SESSION['A'], 'ab');
	}

	protected function setUri(string $uri) : void
	{
		$_SERVER['REQUEST_URI'] = $uri;
	}

}
