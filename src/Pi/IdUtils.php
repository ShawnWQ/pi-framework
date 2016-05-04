<?hh

namespace Pi;

class IdUtils {

  public static function createUrn($id, $type)
  {
    $name = is_string($type) ? $type : get_class($type);

    return sprintf('urn:%s:%s', $name, $id);
  }
}
