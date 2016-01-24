<?hh

namespace Pi\Host;

use Pi\Interfaces\IHttpResponse;
use Pi\Interfaces\IHttpRequest;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\Host\HostProvider;

class PhpResponse extends BasicResponse implements IHttpResponse {

	protected Map<string,string> $headers = Map{};

	protected Map<string,string> $cookies = Map{};

	protected $headersSent = false;

	public function endRequest($skipHeaders = true) : void
	{
		if(!$skipHeaders) {
		//	$this->setHeaders();
		}

		HostProvider::instance()->endRequest();
	}

  	public function headers() : Map<string,string>
	{
		return $this->headers;
	}

	public function addHeader(string $key, mixed $value)
	{
		
		$this->headers->add(Pair{$key, (string)$value});
	}

	public function cookies() : Map<string,string>
	{
		return $this->headers;
	}

	public function write($text, int $responseStatus = 200) : void
	{
		$this->setHeaders();
		parent::write($text, $responseStatus);
	}

	public function writeDto(IRequest $httpRequest, $dto) : void
	{
		$this->setHeaders();
		parent::writeDto($httpRequest, $dto);
	}

	public function setHeaders()
	{
		$this->assertHeadersPristine();   
		if(isset($_SERVER['HTTP_ORIGIN'])) {
		    //header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
		    //header('Access-Control-Allow-Credentials: true');
		    //header('Access-Control-Max-Age: 86400');    // cache for 1 day
			$this->processCrossOrigin();
		}
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            //header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
            //header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            $this->processOptions();
        }
		
		foreach($this->headers as $key => $value) {
			 header($key . ': ' . $value);
		}
		$this->headersSent = true;

	}

	protected function assertHeadersPristine() : void
	{
		if($this->headersSent) {
			throw new \Exception('Headers already sent and shouldnt');
		}
	}

	protected function processOptions()
	{
		$this->addHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE');
		$this->addHeader('Access-Control-Allow-Headers', "{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
	}

	protected function processCrossOrigin()
	{
		$this->addHeader('Access-Control-Allow-Origin', "{$_SERVER['HTTP_ORIGIN']}");
		$this->addHeader('Access-Control-Allow-Credentials', 'true');
		$this->addHeader('Access-Control-Max-Age', '86400');
	}
}
