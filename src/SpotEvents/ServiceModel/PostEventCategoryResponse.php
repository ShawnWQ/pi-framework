<?hh

namespace SpotEvents\ServiceModel;

use Pi\Response;


class PostEventCategoryResponse extends Response {

  protected EventCategoryDto $category;

  public function getCategory() : EventCategoryDto
  {
    return $this->category;
  }

  public function setCategory(EventCategoryDto $value) : void
  {
    $this->category = $value;
  }
}
