<?hh

namespace Pi\ServiceModel;

class FindArticleCategoryRequest extends RequestQueryAbstract {

  protected ?string $categoryId;

  public function getCategoryId() : ?string
  {
    return $this->categoryId;
  }

  public function setCategoryId(string $value) : void
  {
    $this->categoryId = $value;
  }
}
