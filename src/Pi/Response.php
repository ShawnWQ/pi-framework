<?hh

namespace Pi;

abstract class Response implements \JsonSerializable {

  public function jsonSerialize()
  {
    return get_object_vars($this);
  }
}
