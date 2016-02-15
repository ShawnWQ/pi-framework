<?hh

namespace Pi\Redis;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;
use Pi\Redis\Interfaces\IRedisClient;

class RedisClient
  extends \Redis
  implements IContainable, IRedisClient {

  public $client;
  private $socket;

  public function ioc(IContainer $container){}

  public function __construct(string $hostname = 'localhost', int $port = 6067)
  {
    $this->client =  new \Redis();
    $this->client->connect($hostname);
  }

  public function begin()
  {

  }

  public function connect()
  {

  }

  public function get($key)
  {
    return $this->client->get($key);
  }

  public function set($key, $value)
  {
    if($value instanceof \JsonSerializable) {
      $value = json_encode($value->jsonSerialize());
    }
    return $this->client->set($key, $value); //ini_get('session.gc_maxlifetime')
  }

  public function sadd($set, $key)
  {
    return $this->client->sadd($set, $key);
  }

  public function smembers($set)
  {
    return $this->client->smembers($set);
  }

  public function hset(string $hash, string $field, $value)
  {
    $this->client->hset($hash, $field, $value);
  }

  public function hgetAll(string $hash)
  {
    return $this->client->hgetall($hash);
  }

  public function hget(string $hash, string $key)
  {
    return $this->client->hget($hash, $key);
  }

  public function incr(string $key, $incryBy = 1)
  {
    $this->client->incr($key, $incryBy);
  }

  public function lpush(string $key, $value)
  {
    $this->client->lpush($key, $value);
  }

  public function llen(string $key)
  {
    $this->client->llen($key);
  }

  public function lrange(string $key, int $start, int $stop)
  {
    return $this->client->lrange($key, $start, $stop);
  }

  public function gc($maxlifetime) {
      // Handled by $redis->set(..., ini_get('session.gc_maxlifetime'))
      return 0;
  }

  public function delete(string $key)
  {
    $this->client->delete($key);
  }
  
  public function del(string $key)
  {
    return $this->client->del($key);
  }

  public function srem(string $set, $key)
  {
    return $this->client->srem($set, $key);
  }

  public function client()
  {
    return $this->client;
  }
}
