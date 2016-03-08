<?hh

namespace Pi\Redis;

class RedisLocalClientManager extends RedisClientManager {

  public function __construct(protected RedisFactory $factory)
  {
    parent::__construct($factory);
  }

  public function getClient()
  {
    return $this->createClient();
  }

  private function createClient()
  {
    return $this->factory->createClient();
  }
}
