<?hh

use Pi\Response,
    Pi\Host\Handlers\RestHandler,
    Pi\Host\Handlers\AbstractPiHandler,
    Pi\Interfaces\IRequest;


class AbstractPiHandlerReqMock { }
class AbstractPiHandlerResMock extends Response { }
class AbstractPiHandlerMock extends AbstractPiHandler {

  public function createResponse(IRequest $request, $requestDto)
  {
    return new AbstractPiHandlerResMock();
  }

  public function createRequest(IRequest $request, string $operationName)
  {
    return new AbstractPiHandlerReqMock();
  }

}

class AbstractPiHandlerTest extends \PHPUnit_Framework_TestCase {
  
  protected AbstractPiHandler $handler;

  public function setUp()
  {
    $host = new BibleHost();
    $this->handler = new AbstractPiHandlerMock();
  }

  public function testCanCreateRequest()
  {
    $_SERVER['REQUEST_URI'] = '/verse/5';
    $_SERVER['REQUEST_METHOD'] = 'GET';
      
  }

  
}
