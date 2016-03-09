<?hh

namespace Pi\Cache;

use Pi\Interfaces\ICacheProvider,
    Pi\Interfaces\IContainable,
    Pi\Interfaces\IContainer,
    Pi\Host\HostProvider,
    Pi\Common\RandomString,
    Pi\Redis\Interfaces\IRedisClient;




/**
 * Redis Cache Provider
 * Uses a redis Set to store key/value data.
 * The set is prefixed with RedisCacheProvider::PREFIX
 */
class RedisCacheProvider implements ICacheProvider, IContainable {

  const PREFIX = 'app-config::';

  public function __construct(
    protected IRedisClient $redis)
  {

  }

  public function ioc(IContainer $container) { }

  protected function redisSet() : string
  {
    return self::PREFIX;
  }

  public function get($key = null)
  {
    return is_null($key) ? $this->redis->get($this->redisSet()) :  $this->redis->get($this->redisSet() . $key);
  }

  public function set($key, $value)
  {
    $this->redis->set($this->redisSet() . $key, $value);
  }

  public function expire($key, int $seconds)
  {
    $this->redis->expire($this->redisSet() . $key, $seconds);
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
