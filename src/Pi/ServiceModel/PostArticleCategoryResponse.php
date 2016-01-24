<?hh

namespace Pi\ServiceModel;

use Pi\Response;


class PostArticleCategoryResponse extends Response {

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
