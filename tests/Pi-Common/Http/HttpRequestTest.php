<?hh

use Pi\Interfaces\IRequest,
    Pi\Interfaces\IResponse,
    Pi\Common\Http\HttpRequest,
    Pi\Common\Http\HttpMessage;

class HttpRequestTest extends \PHPUnit_Framework_TestCase {

  
  public function testCanAddResponseFilter()
  {
    $intercepted = false;
    $request = new HttpRequest('https://facebook.com/100006410104051');
    $request->responseFilters()->add(function(IResponse $response) use($intercepted) {
      $this->assertTrue($response->headers()->contains('Location'));

    });
    $request->send();
    $this->assertTrue($intercepted);
  }

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
