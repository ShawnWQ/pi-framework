<?hh

namespace Mocks;
use Pi\Host\PhpRequest;

class HttpRequestMock
  extends PhpRequest {

  	public function __construct($request)
  	{
  		parent::__construct();
  		$this->setDto($request);
  	}
}
