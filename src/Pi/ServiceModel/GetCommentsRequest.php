<?hh

namespace Pi\ServiceModel;

class GetCommentsRequest {

  protected \MongoId $entityId;

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
  public function getNamespace()
  {
    return $this->namespace;
  }

  public function setNamespace(string $value)
  {
    $this->namespace = $value;
  }
}
