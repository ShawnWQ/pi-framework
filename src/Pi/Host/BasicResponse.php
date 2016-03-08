<?hh

namespace Pi\Host;

use Pi\Extensions,
    Pi\HttpResult,
    Pi\SessionPlugin,
    Pi\Interfaces\IResponse,
    Pi\Interfaces\IRequest,
    Pi\Interfaces\IContainable,
    Pi\Interfaces\IContainer;




class BasicResponse implements IResponse{

    protected $isClosed = false;

    protected $memoryStream;

    protected int $statusCode = 200;

    protected string $statusDescription;

    protected Map<string,string> $headers = Map{};

    protected Map<string,string> $cookies = Map{};

    protected $headersSent = false;

    static function createSessionIds(IResponse $res, IRequest $request)
    {
      if(is_null($request->getPermanentSessionId())) {

       BasicResponse::createPermanentSessionId($res, $request);
      }

      if(is_null($request->getTemporarySessionId())) {
        BasicResponse::createTemporarySessionId($res, $request);
      }
    }

    static function createTemporarySessionId(IResponse &$res, IRequest &$request)
    {
      $sessionId = self::createRandomSessionId();

      if($res instanceof IResponse) {
        $res->cookies()->add(Pair{SessionPlugin::SessionId, $sessionId});
        $res->headers()->add(Pair{SessionPlugin::SessionId, $sessionId});
      }

      $request->itemsRef()[SessionPlugin::SessionId] = $sessionId;
      return $sessionId;
    }

    static function createPermanentSessionId(IResponse $res, IRequest $request)
    {
      $sessionId = self::createRandomSessionId();

      if($res instanceof IResponse) {
        $res->cookies()->add(Pair{SessionPlugin::PermanentSessionId, $sessionId});
        $res->headers()->add(Pair{SessionPlugin::PermanentSessionId, $sessionId});
      }

      $request->itemsRef()[SessionPlugin::PermanentSessionId] = $sessionId;

      return $sessionId;
    }

    static function createRandomSessionId()
    {
      return \Pi\Common\RandomString::generate();
    }

    public function endRequest($skipHeaders = true) : void
    {
      if(!$skipHeaders) {
        $this->setHeaders();
      }
//      die();
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

    public function write($text, ?int $responseStatus = null) : void
    {
      if(Extensions::testingMode()) {
        return;
      }
      
      $this->setHeaders();
      $this->setCookies();

        
      if(!empty($output)){
        throw new \Exception('Should not have output');
      }
      
      ob_end_clean();
      
      http_response_code($responseStatus ?: $this->statusCode);
      echo nl2br($text);
    }

    public function writeDto(IRequest $httpRequest, $dto) : void
    {
      if(Extensions::testingMode()) {
        return;
      }
      $this->setHeaders();
      $this->setCookies();
      $output = ob_get_contents();
      
      if(!empty($output)){
        throw new \Exception('Should not have output');
      }
      
      ob_end_clean();

      if($dto instanceof HttpResult) {
        http_response_code($dto->status());
        echo json_encode($dto->response());
      } else if(!is_null($dto)) {
        http_response_code($this->statusCode);
        echo json_encode($dto);
      } else {
        return;
      }
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
//        $this->processCrossOrigin();
      }

      if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  //        $this->processOptions();
      }
      
      foreach($this->headers as $key => $value) {
         header($key . ': ' . $value);
      }
      $this->headersSent = true;
    }

    protected function assertHeadersPristine() : void
    {
      if($this->headersSent) {
     //   throw new \Exception('Headers already sent and shouldnt');
      }
    }

    protected function processOptions()
    {
      $this->addHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE');
      $this->addHeader('Access-Control-Allow-Headers', "{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }

    protected function processCrossOrigin()
    {
      $this->addHeader('Access-Control-Allow-Origin', "*");
      $this->addHeader('Access-Control-Allow-Credentials', 'true');
      $this->addHeader('Access-Control-Max-Age', '86400'); // one day
    }
}
