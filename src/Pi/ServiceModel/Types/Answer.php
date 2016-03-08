<?hh

namespace Pi\ServiceModel\Types;

<<Collection('qa-answer')>>
class Answer extends CreativeWork {

  protected string $answerBody;

  public function getAnswerBody() : string
  {
    return $this->answerBody;
  }

  public function setAnswerBody(string $value) : void
  {
    $this->answerBody = $value;
  }
}
