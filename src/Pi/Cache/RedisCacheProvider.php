<?hh

namespace Pi\Cache;

use Pi\Interfaces\ICacheProvider,
    Pi\Interfaces\IContainable,
    Pi\Interfaces\IContainer,
    Pi\Interfaces\ISerializerService,
    Pi\Interfaces\InitializeInterface,
    Pi\Host\HostProvider,
    Pi\Common\RandomString,
    Pi\Redis\Interfaces\IRedisClient,
    Pi\Redis\Interfaces\IRedisFactory,
    Pi\Redis\RedisLocalClientManager;




/**
 * Redis Cache Provider
 * Uses a redis Set to store key/value data.
 * The set is prefixed with RedisCacheProvider::PREFIX
 */
class RedisCacheProvider implements ICacheProvider, IContainable {

    const PREFIX = 'cache::';

    protected IRedisClientsManager $redisClientsManager;

    public function __construct(
      )
    {
                
      $this->redisClientsManager = new RedisLocalClientManager(HostProvider::assertAppHost()->tryResolve(IRedisFactory::class));
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

    /**
     * Get the array of the given key
     * @param string $key the array key
     * @return ?array array or null if not exists
     */
    public function getArray(string $key) : ?array
    {
      return (array)$this->get($key);
    }

    public function getAs(string $key, string $className) : ?mixed
    {
      return $this->redis->getAs($key, $className);
    }

    public function set($key, $value)
    {
      $this->redis->set($this->redisSet() . $key, $value);
    }

    public function expire($key, int $seconds)
    {
      $this->redis->expire($this->redisSet() . $key, $seconds);
    }

    /**
     * Push the value to the given key
     * If key not exists, create a new array
     * @param string $key the array key
     * @return void
     */
    public function push(string $key, string $value) : void
    {
      $this->redis->push($this->redisSet(). $key, $value);
    }

    public function add($list, $key, $value)
    {
      $this->redis->hset($this->redisSet(). $list, $key, $value);
    }

    public function addToList(string $key, $value)
    {
      $this->redis->lpush($key, $value);
    }

     /**
     * Push the object to the given key
     * If the key not exists, create a new array
     * @param string $key the array key
     * @param mixed $obj the object
     * @return void
     */
    public function pushObject(string $key, mixed $obj) : void
    {

    }

    /**
     * Get the map for the given key
     * @param string $key the array key
     * @return ?Map<string,string> the map, if not exists null
     */
    public function getMap(string $key) : ?Map<string,string>
    {
      $map = $this->get($key);
      return unserialize($map);
    }

    /**
     * Push the given key and value to array key
     * @param string $key the array key
     * @param string $mapKey the map key
     * @param string $mapValue the map value
     * @return void
     */
    public function pushMap(string $key, string $mapKey, string $mapValue) : void
    {
      $map = $this->get($key);
      if($map == $null) {
        $map = Map{};
      }
      $map->add(Pair{$mapKey, $mapValue});
    }

    public function contains($key) : bool
    {
      return $this->get($key) !== null;
    }

    /**
     * Indicates if the given key exists
     * @param string $key the key name
     * @return bool true if the key exits
     */
    public function containsList($list, $key) : bool
    {
      $v = $this->redis->hget($this->redisSet() . $list, $key);
      return !is_null($v) && is_string($v);
    }
}
