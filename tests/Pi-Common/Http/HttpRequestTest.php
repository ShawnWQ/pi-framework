<?hh

use Pi\Common\Http\HttpRequest;
use Pi\Common\Http\HttpMessage;

class HttpRequestTest extends \PHPUnit_Framework_TestCase {

  
  public function testCanSendGetRequest()
  {
  	$request = new HttpRequest('https://google.com', HttpRequest::METHOD_GET);
  	$message = $request->send();
  	$this->assertTrue($message instanceof HttpMessage);
  }

  public function testCanSendPostRequest()
  {
  	$request = new HttpRequest('https://google.com', HttpRequest::METHOD_POST);
  	$message = $request->send();
  	$this->assertTrue($message instanceof HttpMessage);

  	$this->assertEquals($message->getRequestMethod(), HttpRequest::METHOD_POST);
  }
}
