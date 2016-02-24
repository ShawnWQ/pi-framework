<?hh

namespace Pi\Host;

use Pi\Extensions,
	Pi\Interfaces\IHttpResponse,
	Pi\Interfaces\IHttpRequest,
	Pi\Interfaces\IRequest,
	Pi\Interfaces\IResponse,
	Pi\Host\HostProvider;

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
		return $this->cookies;
	}

	public function write($text, int $responseStatus = 200) : void
	{
		$this->setHeaders();
		$this->setCookies();
		parent::write($text, $responseStatus);
	}

	public function writeDto(IRequest $httpRequest, $dto) : void
	{
		$this->setHeaders();
		$this->setCookies();
		parent::writeDto($httpRequest, $dto);
	}

	protected function setCookies()
	{
		if(Extensions::testingMode()) return;

		foreach ($this->cookies as $key => $value) {
			$domain = HostProvider::instance()->config()->domain();
			setcookie($key, $value, time() + 60*60*24*365, '/', $domain);
		}
	}

	public function setHeaders()
	{
		if(Extensions::testingMode()) return;
		
		$this->assertHeadersPristine();   
		
		if(isset($_SERVER['HTTP_ORIGIN'])) {
			$this->processCrossOrigin();
		}

        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
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
		$this->addHeader('Access-Control-Max-Age', '86400'); // one day
	}
}
