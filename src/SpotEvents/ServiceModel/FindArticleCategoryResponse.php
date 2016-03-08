<?hh

namespace SpotEvents\ServiceModel;

use Pi\Response;


class FindEventCategoryResponse extends Response {

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
