<?hh

namespace Pi\ServiceModel;

use Pi\Response;


class PostQuestionCategoryResponse extends Response {

  protected QuestionCategoryDto $category;

  public function getCategory() : QuestionCategoryDto
  {
    return $this->category;
  }

  public function setCategory(QuestionCategoryDto $cat) : void
  {
    $this->category = $cat;
  }
}
