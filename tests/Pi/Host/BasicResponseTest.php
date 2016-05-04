<?hh

namespace Pi\Host;
use Pi\Host\BasicRequest;
use Pi\Host\BasicResponse;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\SessionPlugin;
use Mocks\VerseCreateRequest;
use Mocks\BibleHost;

class BasicResponseTest extends \PHPUnit_Framework_TestCase {

  public function testCanCreateTemporarySession()
  {
    $req = new BasicRequest();
    $res = new BasicResponse();
    BasicResponse::createTemporarySessionId($res, $req);
    $this->assertTrue(isset($req->items()[SessionPlugin::SessionId]));
  }
	public function testCanCreatePermanentSession()
	{
		$req = new BasicRequest();
    $res = new BasicResponse();
    BasicResponse::createPermanentSessionId($res, $req);
    $this->assertTrue(isset($req->items()[SessionPlugin::PermanentSessionId]));
	}
}
