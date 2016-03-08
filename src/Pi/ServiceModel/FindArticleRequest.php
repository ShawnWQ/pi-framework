<?hh

namespace Pi\ServiceModel;

class FindArticleRequest extends RequestQueryAbstract {

  protected ?string $categoryId;

  protected ?string $name;

  protected $state;

  public function getName() : ?string
  {
    return $this->name;
  }

  public function setName(string $name) : void
  {
    $this->name = $name;
  }

  public function getCategoryId() : ?string
  {
    return $this->categoryId;
  }

  public function setCategoryId(string $value) : void
  {
    $this->categoryId = $value;
  }

  <<Enumerator('Pi\ServiceModel\ArticleState')>>
  public function getState() : ?ArticleState
  {
    return $this->state;
  }

  public function setState(ArticleState $state) : void
  {
    $this->state = $value;
  }
}
