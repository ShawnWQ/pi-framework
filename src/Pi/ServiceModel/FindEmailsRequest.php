<?hh

namespace Pi\ServiceModel;

class FindEmailsRequest {

  protected string $to;

  <<String>>
  public function getTo()
  {
    return $this->to;
  }

  public function setTo(string $value)
  {
    $this->to = $value;
  }
}
