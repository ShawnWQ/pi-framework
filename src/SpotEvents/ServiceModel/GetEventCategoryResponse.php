<?hh

namespace SpotEvents\ServiceModel;

use Pi\Response;


class GetEventCategoryResponse extends Response {

  protected ArticleCategoryDto $category;

  public function getCategory() : ArticleCategoryDto
  {
    return $this->category;
  }

  public function setCategory(ArticleCategoryDto $value) : void
  {
    $this->category = $value;
  }
}
