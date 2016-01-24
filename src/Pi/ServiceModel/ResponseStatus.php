<?hh

namespace Pi\ServiceModel;

class ResponseStatus {

  public function __construct()
  {
    $this->errors = Set{};
  }

  public function getErrorCode()
  {
    return $this->errorCode;
  }
  public function setErrorCode($errorCode)
  {
    $this->errorCode = $errorCode;
  }

  public function getMessage()
  {
    return $this->message;
  }
  public function setMessage(string $message) : void
  {
    $this->message = $message;
  }

  public function getStackTrace()
  {
    return $this->stackTrace;
  }
  public function setStackTrace($stackTrace)
  {
    $this->stackTrace = $stackTrace;
  }

  protected $errorCode;

  protected $message;

  protected $stackTrace;

  /**
   * Response errors
   * @var ResponseError[]
   */
  protected $errors;
}
