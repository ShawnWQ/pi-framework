<?hh

namespace Warez\ServiceModel;

class GetMovieRequest {

  protected \MonogId $id;

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
