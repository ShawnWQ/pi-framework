<?hh

namespace Pi\ServiceModel;

class FindQuestionCategoryRequest extends RequestQueryAbstract {

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
