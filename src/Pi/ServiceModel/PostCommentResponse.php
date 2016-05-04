<?hh

namespace Pi\ServiceModel;

use Pi\Response;

class PostCommentResponse extends Response {

  protected $comment;

  public function getComment()
  {
    return $this->comment;
  }

  public function setComment($value)
  {
    $this->comment = $value;
  }
}
