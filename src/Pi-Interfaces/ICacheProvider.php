<?hh

namespace Pi\Interfaces;

interface ICacheProvider extends IContainable{

  public function ioc(IContainer $container);
  public function get($key = null);
  public function set($key, $value);
}
