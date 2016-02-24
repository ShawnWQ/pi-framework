<?hh

namespace Pi\Host;


use Pi\Extensions;
use Pi\HttpResult;
use Pi\SessionPlugin;
use Pi\Interfaces\IResponse;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IHttpRequest;
use Pi\Interfaces\IHttpResponse;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;

class BasicResponse
  implements IResponse{

    protected $isClosed = false;

    protected $memoryStream;

    protected int $statusCode = 200;

    protected string $statusDescription;

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

      if($res instanceof IHttpResponse) {
        $res->cookies()->add(Pair{SessionPlugin::SessionId, $sessionId});
        $res->headers()->add(Pair{SessionPlugin::SessionId, $sessionId});
      }

      $request->itemsRef()[SessionPlugin::SessionId] = $sessionId;
      return $sessionId;
    }

    static function createPermanentSessionId(IResponse $res, IRequest $request)
    {
      $sessionId = self::createRandomSessionId();

      if($res instanceof IHttpResponse) {
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

    public function write($text, $responseStatus = 200) : void
    {
      if(Extensions::testingMode()) {
        return;
      }
        http_response_code($responseStatus);
        echo nl2br($text);

    }

    public function writeDto(IRequest $httpRequest, $dto) : void
    {
      if(Extensions::testingMode()) {
        return;
      }
      $output = ob_get_contents();
      if(!empty($output)){
        throw new \Exception('Should not have output');
      }
      
      ob_end_clean();

      if($dto instanceof HttpResult) {
        http_response_code($dto->status());
        echo json_encode($dto->response());
      } else if(!is_null($dto)) {
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
}
