<?hh

namespace Pi\ServiceModel;

class GetLikesRequest {

  protected \MongoId $entityId;

  protected string $namespace;

  <<ObjectId>>
  public function getEntityId() : \MongoId
  {
    return $this->entityId;
  }

  public function setEntityId(\MongoId $id) : void
  {
    $this->entityId = $id;
  }

  public function getNamespace() : string
  {
    return $this->namespace;
  }

  public function setNamespace(string $value) : void
  {
    $this->namespace = $value;
  }
}
