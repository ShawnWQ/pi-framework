<?hh

namespace Pi\ServiceModel\Types;

use Pi\Odm\Interfaces\IEntity;

<<Collection('article-category')>>
class ArticleCategoryEmbed implements IEntity, \JsonSerializable{

  public function jsonSerialize()
  {
    return get_object_vars($this);
  }

  protected string $id;

  protected $displayName;

  protected $parent;

  protected ?string $path;

  protected string $uri;

  <<Id>>
  public function id($value = null)
  {
    if(is_null($value)) return $this->id;
    $this->id = $value;
  }

  public function setId(string $id) : void
  {
    $this->id = $id;
  }

  public function getId() : string
  {
    return $this->id;
  }

  <<String>>
  public function getDisplayName()
  {
    return $this->displayName;
  }

  public function setDisplayName(string $value)
  {
    $this->displayName = $value;
  }

  <<String>>
  public function getParent()
  {
    return $this->parent;
  }

  public function setParent($parent)
  {
    $this->parent = $parent;
  }

  <<String>>
  public function getPath() : ?string
  {
    return $this->path;
  }

  public function setPath(string $value) : void
  {
    $this->path = $value;
  }

  <<String>>
  public function getUri() : ?string
  {
    return $this->uri;
  }

  public function setUri(string $value) : void
  {
    $this->uri = $value;
  }
}
