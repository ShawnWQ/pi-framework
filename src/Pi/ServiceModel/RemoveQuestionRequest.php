<?hh

namespace Pi\ServiceModel;


class RemoveQuestionRequest {

  protected \MongoId $id;

  public function getId() : \MongoId
  {
    return $this->id;
  }

  public function setId(\MongoId $id) : void
  {
    $this->id = $id;
  }
}
