<?hh

namespace Pi\Redis;

class RedisConfiguration {

  public function __construct(protected string $hostname = 'localhost', protected int $port = 6667) {

  }

  public function setHostname($hostname)
  {
    $this->hostname = $hostname;
  }

  public function hostname()
  {
    return $this->hostname;
  }

  public function setPort(int $port)
  {
    $this->port = $port;
  }

  public function port() {
    return $this->port;
  }
}
