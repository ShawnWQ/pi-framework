<?hh

namespace Pi\Host;
use Pi\Host\PhpRequest;
use Pi\Interfaces\IRequest;
use Mocks\VerseCreateRequest;
use Mocks\BibleHost;
use Pi\Auth\Interfaces\IAuthSession;

class PhpRequestTest extends \PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$_REQUEST['REQUEST_URI'] = '/test';
	}
	public function testHeadersAreSet()
	{
		$host = new BibleHost();
		$host->init();
		$httpReq = $host->container()->get('IRequest');

		$this->assertTrue($httpReq instanceof IHttpRequest);

		$this->assertTrue($httpReq->headers()['REQUEST_URI'] === '/test');
	}

	public function testSessionIsCreated()
	{
		$host = new BibleHost();
		$host->init();
		$httpReq = $host->container()->get('IRequest');
		$session = $httpReq->getSession();
		$this->assertTrue($session instanceof IAuthSession);

	}
}
