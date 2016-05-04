<?hh

namespace Pi\ServiceModel;

use Pi\Response;

class GetUserFeedResponse extends Response {

  protected $feeds;

  public function setFeeds($feeds)
  {
    $this->feeds = $feeds;
  }

  public function getFeeds()
  {
    return $this->feeds;
  }
}
