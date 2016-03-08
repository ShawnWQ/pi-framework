<?hh

namespace Pi;
use Pi\Interfaces\IServiceSerializer;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;


class PhpSerializerService implements IServiceSerializer, IContainable {
  public function ioc(IContainer $ioc)
  {

  }

  public function serialize($request)
  {
    $result = serialize($request);
    return $result;
  }

  public function deserialize($request)
  {
    $result = unserialize($request);
    return $result;
  }
}
