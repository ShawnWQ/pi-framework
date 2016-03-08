<?hh

namespace SpotEvents\ServiceModel;

class GetWorkoutRequest {

  protected \MongoId $id;

  <<ObjectId>>
  public function getId() : \MongoId
  {
    return $this->id;
  }

  public function setId(\MongoId $id) : void
  {
    $this->id = $id;
  }
}
