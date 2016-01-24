<?hh

namespace Pi\Cache;

use Pi\Interfaces\ICacheProvider;
use Pi\Interfaces\IContainer;

class InMemoryCacheProvider
  implements ICacheProvider{

  protected $config;

  public function __construct()
  {
    $this->config = new \StdClass;
  }
  public function ioc(IContainer $container)
  {

  }

  public function get($key = null)
  {
    if(is_null($key))
      return $this->config;

    return array_key_exists($key, $this->config) ? $this->config->$key : null;
  }

  public function set($key, $value, $persist = true)
  {
    try {
      $this->config->$key = $value;
    }
    catch(\Exception $ex) {
      throw new \Exception(
        sprintf('Error while writting a new value in local cache provider: %s', $ex->getMessage())
      );
    }

  }

  public function push($key, $value)
  {
    if(!array_key_exists($key, $this->config)) {
      $this->config->$key = Set{};
    }
    $this->config->$key->add($value);
  }
}
