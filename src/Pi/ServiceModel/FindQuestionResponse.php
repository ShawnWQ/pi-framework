<?hh

namespace Pi\ServiceModel;
use Pi\Response;

class FindQuestionResponse extends Response {

  protected $questions;

  public function getQuestions()
  {
    return $this->questions;
  }

  public function setQuestions($q)
  {
    $this->questions = $q;
  }
}
