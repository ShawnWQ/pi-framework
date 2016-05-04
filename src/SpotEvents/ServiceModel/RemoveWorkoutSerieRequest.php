<?hh

namespace SpotEvents\ServiceModel;


class RemoveWorkoutSerieRequest {

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
