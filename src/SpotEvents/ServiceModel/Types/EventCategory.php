<?hh

namespace SpotEvents\ServiceModel\Types;

use Pi\Odm\Interfaces\IEntity;

<<MultiTenant,Collection('event-category')>>
class EventCategory implements IEntity {

  protected string $id;

  protected $displayName;

  protected $parent;

  protected ?string $path;

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
}
