<?hh

namespace Pi\ServiceModel;

use Pi\Response;


class FindQuestionCategoryResponse extends Response {

  protected $categories;

  public function getCategories()
  {
    return $this->categories;
  }

  public function setCategories($categories) : void
  {
    $this->categories = $categories;
  }
}
