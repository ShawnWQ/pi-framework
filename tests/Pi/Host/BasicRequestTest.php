<?hh

namespace Pi\Host;

use Pi\Host\BasicRequest,
	Pi\Interfaces\IRequest,
	Mocks\VerseCreateRequest,
	Mocks\BibleHost,
	Pi\Auth\Interfaces\IAuthSession;




class BasicRequestTest extends \PHPUnit_Framework_TestCase {

	protected BibleHost $host;

	public function setUp()
	{
		$this->host = new BibleHost();
		$this->host->init();
	}

	public function tearDown()
	{
		$this->host->dispose();
	}

	public function testHeadersAreSet()
	{
		$host = new BibleHost();
		$httpReq = new BasicRequest();

		$this->assertTrue($httpReq instanceof IRequest);
		$this->assertTrue($httpReq->headers()['REQUEST_URI'] === '/testi');
	}

	public function testSessionIsCreated()
	{
		$httpReq = new BasicRequest();

		$session = $httpReq->getSession();
		$this->assertTrue($session instanceof IAuthSession);
	}
}
