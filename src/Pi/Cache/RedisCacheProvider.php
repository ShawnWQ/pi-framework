<?hh

namespace Pi\Cache;
use Pi\Interfaces\ICacheProvider;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;
use Pi\Host\HostProvider;
use Pi\Common\RandomString;
use Pi\Redis\Interfaces\IRedisClient;

class RedisCacheProvider implements ICacheProvider, IContainable {

  public function __construct(
    protected IRedisClient $redis)
  {

  }

  public function ioc(IContainer $container)
  {


  }

  protected $configs;

  protected function redisSet() : string
  {
    return 'app-config::';
  }

  public function get($key = null)
  {

    return is_null($key) ? $this->redis->get($this->redisSet()) :  $this->redis->get($this->redisSet() . $key);
  }


  public function set($key, $value)
  {
    $this->redis->set($this->redisSet() . $key, $value);
  }

  public function push($key, $value)
  {
    $this->redis->push($this->redisSet(). $key, $value);
  }

  public function add($list, $key, $value)
  {
    $this->redis->hset($this->redisSet(). $list, $key, $value);
  }

  public function contains($list, $key)
  {
    $v = $this->redis->hget($this->redisSet() . $list, $key);
    return !is_null($v) && is_string($v);
  }
}
