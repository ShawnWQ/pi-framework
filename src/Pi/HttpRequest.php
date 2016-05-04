<?hh

namespace Pi;
use Pi\Interfaces\IHasAppHost;

/**
 * Http Request
 *
 * The principal request. Access to request information as verbe, rawInput, headers, cookies
 * MqRequests have a diferent Request
 */
class HttpRequest implements IHasAppHost{

  protected $appHost;

  protected $verbe;

  protected $preferences;

  protected $dto;

  protected $remoteIp;

  protected $rawInput;

  protected $errorStream;

  public function __construct()
  {
    $this->verbe = $_SERVER['REQUEST_METHOD'];
    $this->remoteIp = $_SERVER['REMOTE_ADDR'];
    //Input stream (readable one time only; not available for multipart/form-data requests)
    $rawInput = @file_get_contents('php://input');
    if (!$rawInput) {
        $rawInput = '';
    }
    $this->rawInput = $rawInput;
    $this->errorStream = @fopen('php://stderr', 'w');
  }

  public function getRawBody()
  {
    return '';
  }

  public function remoteIp() : string
  {
    return '';
  }

  public function inputStream()
  {
    return $this->rawInput;
  }

  public function contentLong()
  {

  }

  public function files()
  {
    return $_FILES;
  }

  public function urlReferrer()
  {

  }

  public function dto()
  {
    return $this->dto;
  }

  public function setDto($dto)
  {
    $this->dto = $dto;
  }


  public function appHost(){
      return $this->appHost;
  }

  public function setAppHost($appHost){
    $this->appHost = $appHost;
  }

  public function tryResolve($type)
  {
    return $this->appHost->tryResolve($type);
  }

  public function operationName() : string
  {
    return $this->operationName();
  }

  public function verbe() : string
  {
    return $this->verbe;;
  }
}
