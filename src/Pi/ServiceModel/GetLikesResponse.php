<?hh

namespace Pi\ServiceModel;

use Pi\Response;


class GetLikesResponse extends Response {

  protected $likes;

  /**
   * @return mixed
   */
  public function getLikes()
  {
      return $this->likes;
  }

  /**
   * @param mixed $likes
   */
  public function setLikes($likes)
  {
      $this->likes = $likes;
  }
}
