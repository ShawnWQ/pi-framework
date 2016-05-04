<?hh

namespace Mocks;

use Pi\MockHost,
	Pi\Interfaces\IContainer,
	Pi\Interfaces\ILogFactory,
	Pi\Cache\ApcCacheProvider,
	Pi\MessagePack\MessagePackPlugin,
	SpotEvents\SpotEventsPlugin,
	Warez\WarezPlugin,
	Pi\Redis\RedisPlugin,
	Pi\Redis\Interfaces\IRedisClientsManager,
	Pi\Queue\RedisPiQueue,
	Pi\Queue\PiQueue,
	Pi\ServiceInterface\CorsPlugin,
	Pi\ServiceInterface\PiPlugins,
	Pi\FileSystem\FileSystemPlugin,
	Pi\Validation\ValidationPlugin,
    Pi\ServerEvents\ServerEventsPlugin,
    Pi\Odm\OdmPlugin,
    Pi\Odm\MongoConnectionFactory,
    Pi\Odm\Interfaces\IDbConnectionFactory,
    Pi\Auth\AuthPlugin,
    Pi\Logging\DebugLogFactory,
    Pi\Cache\MemcachedProvider,
    Pi\Cache\RedisCacheProvider,
    Pi\Message\InMemoryFactory;

class BibleHost extends MockHost {

	public function configure(IContainer $container)
	{
		$this->registerPlugin(new ValidationPlugin());
		$this->registerPlugin(new SpotEventsPlugin());
		$this->registerPlugin(new MessagePackPlugin());
		$this->registerPlugin(new WarezPlugin());
		$this->registerPlugin(new SpotEventsPlugin());
		$this->registerPlugin(new ServerEventsPlugin());
		$this->registerPlugin(new OdmPlugin());
		$this->registerPlugin(new FileSystemPlugin());
		$this->registerPlugin(new CorsPlugin());
		$this->registerPlugin(new PiPlugins());
		$this->registerPlugin(new RedisPlugin());
    	$this->registerPlugin(new AuthPlugin());

    	$container->register(IDbConnectionFactory::class, function(IContainer $container){
			$factory = new MongoConnectionFactory();
			$factory->ioc($container);
			return $factory;
		});
		$this->registerCacheProviderInstance(new ApcCacheProvider());
		$this->setMessageFactory(new InMemoryFactory());

		$logFactory = $this->logFactory =  new DebugLogFactory();
		$container->register(ILogFactory::class, function(IContainer $ioc) use($logFactory){
			return $logFactory;
		});
	
    	$this->registerService(BibleTestService::class);
		
		$container->registerRepository(MockEntity::class, EntityRepository::class);
		
		$container->register(PiQueue::class, function(IContainer $ioc) {
	        $factory = $ioc->get(ILogFactory::class);
	        $redis = $ioc->get(IRedisClientsManager::class);
	        $logger = $factory->getLogger(PiQueue::class);
	        return new RedisPiQueue($logger, $redis);
    	});
	}
}
