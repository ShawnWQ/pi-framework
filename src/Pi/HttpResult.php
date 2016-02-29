<?hh
namespace Pi;

use Pi\Interfaces\IIsStreamWriter;
use Pi\HttpHeaders;
use Pi\Validation\ValidationResponse;

class HttpResult implements IIsStreamWriter {

  protected Map<string,string> $headers = Map{};

  public function __construct(
      protected $response = null,
      protected int $status = 200, 
      protected ?string $statusDescription = null)
  {

  }

  public static function notFound(string $message = null, string $code = null)
  {
      if(is_null($message)) {
          $message =  'The document wasnt found.';
      }
      if(is_null($code)) {
          $code = 'notFound';
      }
      $response = array('errorMessage' => $message , 'errorCode' => $code);
      return new HttpResult($response, 404);
  }
  
  public static function createCustomError(string $errorCode, string $errorMessage, ?int $statusCode = null)
  {
    if(is_null($statusCode)) 
    {
      $statusCode = 401;
    }
    $response = array('errorCode' => $errorCode, 'errorMessage' => $errorMessage);
    return new HttpResult($response, $statusCode);
  }

  public static function createFromValidation(ValidationResponse $response)
  {
    return new HttpResult($response, 500);
  }

  public static function redirect(string $redirectUri, HttpStatusCode $statusCode = HttpStatusCode::Found)
  {
      $res = new HttpResult(null, $statusCode);
      $res->status($statusCode);
      $res->setHeader(HttpHeaders::Location, $redirectUri);
      return $res;
  }
  
  public function setHeader($key, $value)
  {
      $this->headers[$key] = $value;
  }

  public function getHeaders()
  {
      return $this->headers;
  }

  public function headers()
  {
    return $this->headers;
  }

  public function writeTo($responseStream)
  {
    echo 'writting to output';
  }

  public function response($value = null)
  {
    if($value === null) return $this->response;
    $this->response = $value;
  }

  public function status(?int $value = null)
  {
    if($value === null) return $this->status;
    $this->status = $value;
  }

  public function statusDescription($value = null)
  {
    if($value === null) return $this->statusDescription;
    $this->statusDescription = $value;
  }
}
