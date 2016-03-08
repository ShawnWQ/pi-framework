<?hh

namespace Pi\ServiceModel;


use Pi\Response;


class PostQuestionResponse extends Response {

  protected QuestionDto $question;

  public function getQuestion() : QuestionDto
  {
    return $this->question;
  }

  public function setQuestion(QuestionDto $dto) : void
  {
    $this->question = $dto;
  }
}
