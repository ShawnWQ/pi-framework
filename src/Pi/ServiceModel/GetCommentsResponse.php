<?hh

namespace Pi\ServiceModel;

use Pi\Response;

class GetCommentsResponse extends Response {

  protected $comments;

  public function getComments()
  {
    return $this->comments;
  }

  public function setComments($comments)
  {
    $this->comments = $comments;
  }
}
