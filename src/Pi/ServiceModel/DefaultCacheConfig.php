<?hh

namespace Pi\ServiceModel;

class DefaultCacheConfig
  implements \JsonSerializable {

  protected $path;

  public function jsonSerialize()
  {
    return get_object_vars($this);
  }

  public function setPath(string $path)
  {
    $this->path = $path;
  }
  public function getPath() : string
  {
    return $this->path;
  }
}
