<?hh

namespace Pi\ServiceModel;
use Pi\Response;
class FindArticleResponse extends Response {

  protected $articles;

  public function getArticles()
  {
    return $this->articles;
  }

  public function setArticles($articles)
  {
    $this->articles = $articles;
  }
}
