<?hh

namespace Pi\FileSystem;
use Pi\Odm\MongoEntity;
use Pi\Odm\Interfaces\IEntity;

<<Collection("FileEntity")>>
class FileEntity implements IEntity, \JsonSerializable {

  protected $url;

  protected $name;

  protected $id;

  protected $ownerToken;

  protected $md5;

  protected $fileName;

  protected $extension;

  protected $ownerId;

  protected $uri;

  public function jsonSerialize()
  {
    $vars = get_object_vars($this);
    $vars['id'] = (string)$vars['id'];
    return $vars;
  }

  <<Id>>
  public function id($id = null)
  {
    if(is_null($id)) return $this->id;
    $this->id = $id;
  }

  <<String>>
  public function extension($value = null)
  {
    if($value === null) return $this->extension;
    $this->extension = $value;
  }

  <<String>>
  public function url($value = null)
  {
    if($value === null) return $this->url;
    $this->url = $value;
  }

  <<String>>
  public function name($value = null)
  {
    if($value === null) return $this->name;
    $this->name = $value;
  }

  <<String>>
  public function fileName($value = null)
  {
    if($value === null) return $this->fileName;
    $this->fileName = $value;
  }

  <<String>>
  public function ownerToken($value = null)
  {
    if($value === null) return $this->ownerToken;
    $this->ownerToken = $value;
  }

  <<Md5>>
  public function md5($value = null)
  {
    if($value === null) return $this->md5;
    $this->md5 = $value;
  }

  <<ObjectId>>
  public function ownerId($value = null)
  {
    if($value === null) return $this->ownerId;
    $this->ownerId = $value;
  }

  <<String>>
  public function uri($value = null)
  {
    if($value === null) return $this->uri;
    $this->uri = $value;
  }
}
