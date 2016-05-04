<?hh

namespace Pi\Memcached;

use Pi\Host\HostProvider,
    Pi\Interfaces\IContainer,
    Pi\Interfaces\IContainable,
    Pi\Memcached\Interfaces\MemcachedFactoryInterface,
    Pi\Memcached\Interfaces\MemcachedClientInterface,
    Pi\Interfaces\HydratorFactoryInterface,
    Pi\Interfaces\ISerializerService;




class MemcachedFactory implements MemcachedFactoryInterface {

    public  function __construct(
        protected HydratorFactoryInterface $hydratorFactory,
        protected ISerializerService $serializer)
    {

    }

    public function createClient(?MemcachedConfiguration $config = null) : IRedisClient
    {
      return is_null($config) ? $this->createDefaultClient() : new RedisClient($this->hydratorFactory, $config->hostname(), $config->port());
    }

    protected function createDefaultClient()
    {
        $factory = HostProvider::tryResolve(HydratorFactoryInterface::class);
        return new MemcachedClient($this->serializer, $this->hydratorFactory);
    }

}
