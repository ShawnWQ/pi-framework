<?hh

namespace SpotEvents\ServiceModel;

use Pi\Odm\Interfaces\IEntity;

<<Collection('event-category')>>
class EventCategoryDto implements IEntity, \JsonSerializable {

  protected $id;

  protected $displayName;

  protected $parent;

  protected ?string $path;

  public function jsonSerialize()
  {
    $vars = get_object_vars($this);
    $vars['id'] = (string)$vars['id'];
    return $vars;
  }

  <<Id>>
  public function id($value = null)
  {
    if(is_null($value)) return $this->id;
    $this->id = $value;
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
  public function getPath() : string
  {
    return $this->path;
  }

  public function setPath(string $value) : void
  {
    $this->path = $value;
  }
}
