<?hh

namespace Pi\Redis;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\IContainable;
use Pi\Redis\Interfaces\IRedisFactory;
use Pi\Redis\Interfaces\IRedisClient,
    Pi\Interfaces\HydratorFactoryInterface;

class RedisFactory implements IContainable, IRedisFactory{

    public  function __construct(
        protected HydratorFactoryInterface $hydratorFactory)
    {

    }

    public function ioc(IContainer $ioc){}

    public function createClient(?RedisConfiguration $config = null) : IRedisClient
    {
      return is_null($config) ? $this->createDefaultClient() : new RedisClient($this->hydratorFactory, $config->hostname(), $config->port());
    }

    protected function createDefaultClient()
    {
      return new RedisClient($this->hydratorFactory);
    }

}
