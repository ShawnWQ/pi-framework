<?hh

use Pi\Common\Http\HttpMessage;

class HttpMessageTest extends \PHPUnit_Framework_TestCase {

  
  public function testCreateObject()
  {
	$message = new HttpMessage('ok');
	$this->assertEquals($message->getBody(), 'ok');
  }
}
