<?hh

namespace Pi\ServiceModel;


class GetPlaceRequest {

  protected \MongoId $id;

  <<ObjectId>>
  public function getId()
  {
    return $this->id;
  }

  public function setId(\MongoId $value)
  {
    $this->id = $value;
  }
}
