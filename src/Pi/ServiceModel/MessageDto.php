<?hh

namespace Pi\ServiceModel;

class MessageDto implements \JsonSerializable {

  protected $author;

  protected string $message;

  protected \DateTime $created;

  public function jsonSerialize()
  {
    $arr = get_object_vars($this);
    return $arr;
  }

  <<Id>>
  public function id($id = null)
  {
    if($id === null) return  $this->id;
    $this->id = $id;
  }

  <<Collection>>
  public function getAuthor()
  {
    return $this->author;
  }

  public function setAuthor($author)
  {
    $this->author = $author;
  }

  <<String>>
  public function getMessage()
  {
    return $this->message;
  }

  public function setMessage(string $msg)
  {
    $this->message = $msg;
  }

  <<DateTime>>
  public function getCreated()
  {
    return $this->created;
  }

  public function setCreated(\DateTime $when)
  {
    $this->created = $when;
  }
}
