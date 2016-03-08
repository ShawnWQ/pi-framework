<?hh

namespace Pi\FileSystem;

use Pi\Odm\Interfaces\IEntity;

<<Collection("FileEntity")>>
class File implements \JsonSerializable, IEntity {

  protected $name;

  protected $type;

  protected $size;

  protected $tmpName;

  protected $error;

  protected $extension;

  protected $uri;

  public function jsonSerialize()
  {
    $vars = get_object_vars($this);
    $vars['id'] = (string)$vars['id'];
    return $vars;
  }

  public function name($value = null)
  {
    if($value === null) return $this->name;
    $this->name = $value;
  }

  public function type($value = null)
  {
    if($value === null) return $this->type;
    $this->type = $value;
  }

  public function size($value = null)
  {
    if($value === null) return $this->size;
    $this->size = $value;
  }

  public function tmpName($value = null)
  {
    if($value === null) return $this->tmpName;
    $this->tmpName = $value;
  }

  public function error($value = null)
  {
    if($value === null) return $this->error;
    $this->error = $value;
  }

  public function extension($value = null)
  {
    if($value === null) return $this->extension;
    $this->extension = $value;
  }

  <<String>>
  public function uri($value = null)
  {
    if($value === null) return $this->uri;
    $this->uri = $value;
  }
}
