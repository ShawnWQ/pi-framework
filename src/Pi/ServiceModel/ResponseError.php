<?hh

namespace Pi\ServiceModel;

class ResponseError {

  public function getCode()
  {
    return $this->code;
  }
  public function setCode($code) : void
  {
    $this->code = $code;
  }

  public function getFieldName() : string
  {
    return $this->fieldName;
  }
  public function setFieldName(string $fieldName) : void
  {
    $this->fieldName = $fieldName;
  }

  public function getMessage() : string
  {
    return $this->message;
  }
  public function setMessage(string $message) : void
  {
    $this->message = $message;
  }

  protected $code;

  protected $fieldName;

  protected $message;
}
