<?hh
use Mocks\OdmContainer;
use Pi\Host\Handlers\RestHandler;
use Mocks\BibleHost;
use Pi\Service;
use Pi\Response;
use MarkdownView\MarkdownHandler;

class MHHtml {

	protected ?\MongoId $id;

	public function getId() : ?\MongoId
	{
		return $this->id;
	}

	public function setId(\MongoId $id) : void
	{
		$this->id = $id;
	}
	
}

class HHTmlResponse extends Response {

	protected $body;

	public function getBody()
	{
		return $this->body;
	}

	public function setBody(string $body)
	{
		$this->body = $body;
	}
}

class MHHHost extends BibleHost {

}

class MHHService extends Service {

	<<Request,Route('/test-view'),View('test-page.html')>>
	public function test(MHHTml $request)
	{
		$response = new HHTmlResponse();
		$response->setBody('the body');
		return $response;
	}
}

class MarkdownHandlerTest extends \PHPUnit_Framework_TestCase {

  protected $host;

  public function setUp()
  {
    $this->host = new BibleHost();
  }

  public function testCanHandleAnHtmlExtension()
  {
  	$this->assertTrue(MarkdownHandler::canHandle('tests/ola.html'));
  	$this->assertFalse(MarkdownHandler::canHandle('tests/ola.xss'));
  }
}
