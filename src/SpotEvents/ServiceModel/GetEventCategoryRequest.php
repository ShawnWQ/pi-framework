<?hh

namespace SpotEvents\ServiceModel;

class GetEventCategoryRequest {

  protected string $id;

  <<Id>>
  public function getId() : string
  {
    return $this->id;
  }

  public function setId(string $id)
  {
    $this->id = $id;
  }
}
