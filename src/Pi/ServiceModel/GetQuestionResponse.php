<?hh

namespace Pi\ServiceModel;

use Pi\Response;


class GetQuestionResponse extends Response {

  protected QuestionDto $question;

  protected ?array $answers;

  public function getQuestion()
  {
    return $this->question;
  }

  public function setQuestion(QuestionDto $dto)
  {
    $this->question = $dto;
  }

  public function getAnswers()
  {
    return $this->answers;
  }

  public function setAnswers(array $values)
  {
    $this->answers = $values;
  }
}
