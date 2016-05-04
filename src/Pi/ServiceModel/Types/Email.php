<?hh

namespace Pi\ServiceModel\Types;

class Email {


  protected $id;

  protected string $from;

  protected string $to;

  protected string $subject;

  protected string $body;

  <<Id>>
  public function getId()
  {
    return $this->id;
  }

  public function setId(\MongoId $id) : void
  {
    $this->id = $id;
  }

  public function getFrom()
  {
    return $this->from;
  }

  public function setFrom(string $email) : void
  {
    $this->from = $email;
  }

  public function getTo()
  {
    return $this->to;
  }

  public function setTo(string $email) : void
  {
    $this->to = $email;
  }

  public function getSubject()
  {
    return $this->subject;
  }

  public function setSubject(string $value) : void
  {
    $this->subject = $value;
  }

  public function getBody()
  {
    return $this->body;
  }

  public function setBody(string $value) : void
  {
    $this->body = $value;
  }
}
