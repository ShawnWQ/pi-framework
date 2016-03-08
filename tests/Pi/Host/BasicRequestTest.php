<?hh

namespace Pi\Host;

use Pi\Host\BasicRequest,
	Pi\Interfaces\IRequest,
	Mocks\VerseCreateRequest,
	Mocks\BibleHost,
	Pi\Auth\Interfaces\IAuthSession;




class BasicRequestTest extends \PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$_REQUEST['REQUEST_URI'] = '/test';
	}

	public function testHeadersAreSet()
	{
		$host = new BibleHost();
		$host->init();
		$httpReq = $host->container()->get('IRequest');

		$this->assertTrue($httpReq instanceof IRequest);

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
