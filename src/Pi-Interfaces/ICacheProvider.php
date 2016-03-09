<?hh

namespace Pi\Interfaces;




/**
 * Cache Provider 
 */
interface ICacheProvider extends IContainable{

  public function ioc(IContainer $container);

  /**
   * Get the value of the given key
   * @param  string $key the key
   * @return ?string      the value or null if not exists
   */
  public function get($key = null);

  /**
   * Set the value of the given key
   * @param string $key   the key
   * @param scalar $value the value
   */
  public function set($key, $value);

}
