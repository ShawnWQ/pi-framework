<?hh

namespace Pi\ServiceModel;
use Pi\Response;

class PostArticleResponse extends Response {

  protected ArticleDto $article;

  public function getArticle() : ArticleDto
  {
    return $this->article;
  }

  public function setArticle(ArticleDto $dto) : void
  {
    $this->article = $dto;
  }
}
