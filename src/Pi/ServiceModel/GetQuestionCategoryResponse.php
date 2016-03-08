<?hh

namespace Pi\ServiceModel;

use Pi\Response;


class GetQuestionCategoryResponse extends Response {

  protected QuestionCategoryDto $category;

  public function getCategory() : QuestionCategoryDto
  {
    return $this->category;
  }

  public function setCategory(QuestionCategoryDto $value) : void
  {
    $this->category = $value;
  }
}
