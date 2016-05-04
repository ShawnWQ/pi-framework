<?hh

namespace Pi\ServiceModel;
use Pi\Response;

class GetArticleSerieResponse extends Response {

  protected ArticleSerieDto $serie;

  public function getSerie() : ArticleSerieDto
  {
    return $this->article;
  }

  public function setSerie(ArticleSerieDto $dto) : void
  {
    $this->serie = $dto;
  }
}
