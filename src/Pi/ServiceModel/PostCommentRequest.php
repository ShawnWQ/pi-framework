<?hh


namespace Pi\ServiceModel;

class PostCommentRequest {

  protected \MongoId $entityId;

  protected string $message;

  protected string $namespace;

  <<ObjectId>>
  public function getEntityId()
  {
    return $this->entityId;
  }

  public function setEntityId(\MongoId $id)
  {
    $this->entityId = $id;
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

  <<String>>
  public function getNamespace()
  {
    return $this->namespace;
  }

  public function setNamespace(string $value)
  {
    $this->namespace = $value;
  }
}
