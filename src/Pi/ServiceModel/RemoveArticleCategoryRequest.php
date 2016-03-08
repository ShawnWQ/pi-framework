<?hh

namespace Pi\ServiceModel;


class RemoveArticleCategoryRequest {

  protected string $id;

  public function getId() : string
  {
    return $this->id;
  }

  public function setId(string $id) : void
  {
    $this->id = $id;
  }
}
