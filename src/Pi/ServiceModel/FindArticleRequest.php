<?hh

namespace Pi\ServiceModel;

class FindArticleRequest extends RequestQueryAbstract {

  protected ?string $categoryId;

  protected ?string $name;

  public function getName() : ?string
  {
    return $this->name;
  }

  public function setName(string $name) : void
  {
    $this->name = $name;
  }

  public function getCategoryId() : ?string
  {
    return $this->categoryId;
  }

  public function setCategoryId(string $value) : void
  {
    $this->categoryId = $value;
  }
}
