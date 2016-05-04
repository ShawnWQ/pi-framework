<?hh

namespace Pi\Redis;

use Pi\Host\HostProvider,
    Pi\Interfaces\IContainer,
    Pi\Interfaces\IContainable,
    Pi\Redis\Interfaces\IRedisFactory,
    Pi\Redis\Interfaces\IRedisClient,
    Pi\Interfaces\HydratorFactoryInterface,
    Pi\Interfaces\ISerializerService;




class RedisFactory implements IRedisFactory{

    public  function __construct(
        protected HydratorFactoryInterface $hydratorFactory,
        protected ISerializerService $serializer)
    {

    }

    public function createClient(?RedisConfiguration $config = null) : IRedisClient
    {
      return is_null($config) ? $this->createDefaultClient() : new RedisClient($this->hydratorFactory, $config->hostname(), $config->port());
    }

    protected function createDefaultClient()
    {
        $factory = HostProvider::tryResolve(HydratorFactoryInterface::class);
        return new RedisClient($this->serializer, $this->hydratorFactory);
    }

}
