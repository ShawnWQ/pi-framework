<?hh

namespace Pi\Common\Http;

use Pi\Extensions;
use Pi\HttpResult;
use Pi\SessionPlugin;
use Pi\Interfaces\IResponse;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;




class PiClientResponse implements IResponse {
	protected $isClosed = false;

    protected $memoryStream;

    protected int $statusCode = 200;

    protected string $statusDescription;

    protected Map<string,string> $headers = Map{};

    protected Map<string,string> $cookies = Map{};

    protected $headersSent = false;

    public function endRequest($skipHeaders = true) : void
    {
      if(!$skipHeaders) {
      //  $this->setHeaders();
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

    public function setHeaders() 
    {
    	
    }

    public function cookies() : Map<string,string>
    {
      return $this->cookies;
    }

    public function getStatusCode() : int
    {
      return $this->statusCode;
    }

    public function setStatusCode(int $code) : void
    {
      $this->statusCode = $code;
    }

    public function getStatusDescription() : string
    {
      return $this->statusDescription;
    }

    public function setStatusDescription(string $desc) : void
    {
      $this->statusDescription = $desc;
    }

    public function ioc(IContainer $ioc)
    {

    }

    public function write($text, int $responseStatus = 200) : void
    {
    
    }

    public function writeDto(IRequest $httpRequest, $dto) : void
    {
    
    }

    /**
     * Signal that this response has been handled and no more processing should be done.
     * When used in a request or response filter, no more filters or processing is done on this request.
     */
    public function close() : void
    {
      $this->isClosed = true;
    }

    public function isClosed() : bool
    {
      return $this->isClosed;
    }

    public function memoryStream()
    {
      return is_null($this->memoryStream) ? new MemoryStream() : $this->memoryStream;
    }
}