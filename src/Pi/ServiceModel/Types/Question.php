<?hh

namespace Pi\ServiceModel\Types;

<<Collection('qa-question')>>
class Question extends CreativeWork {

  protected string $questionBody;

  protected ?string $categoryPath;

  <<String>>
  public function getCategoryPath() : ?string
  {
    return $this->categoryPath;
  }

  public function setCategoryPath(string $value) : void
  {
    $this->categoryPath = $value;
  }

  public function getQuestionBody() : string
  {
    return $this->questionBody;
  }

  public function setQuestionBody(string $value) : void
  {
    $this->questionBody = $value;
  }
}
