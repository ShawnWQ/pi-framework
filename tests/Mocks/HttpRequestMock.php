<?hh

namespace Mocks;

use Pi\Host\BasicRequest;




class HttpRequestMock extends BasicRequest {

  	public function __construct($request)
  	{
  		parent::__construct();
  		$this->setDto($request);
  	}
}
