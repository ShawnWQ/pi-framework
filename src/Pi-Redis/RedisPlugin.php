<?hh

namespace Pi\Redis;

use Pi\AppSettings,
	Pi\Host\HostProvider,
	Pi\Interfaces\IPlugin,
	Pi\Interfaces\IPreInitPlugin,
	Pi\Interfaces\IPiHost,
	Pi\Interfaces\IContainer,
	Pi\Interfaces\ISerializerService,
	Pi\Redis\Interfaces\IRedisFactory,
	Pi\Redis\Interfaces\IRedisClientsManager,
	Pi\Interfaces\IHasGlobalAssertion,
	Pi\Cache\RedisCacheProvider;




class RedisPlugin implements IPreInitPlugin {

	public function register(IPiHost $host) : void
	{
		
	}
	public function configure(IPiHost $host) : void
	{
		$host->container()->register(IRedisFactory::class, function(IContainer $ioc){

			$hydrator = new RedisHydratorFactory(
				$ioc->get('ClassMetadataFactory'),
				'Mocks\\Hydrators',
				sys_get_temp_dir()
       		);

       		$serializer = $ioc->get(ISerializerService::class);
			return new RedisFactory($hydrator, $serializer);
		});
		$host->container()->registerAlias(IRedisFactory::class, 'IRedisFactory');
		$host->container()->register(IRedisClientsManager::class, function(IContainer $ioc){
			$factory = $ioc->get(IRedisFactory::class);

			if(!$factory instanceof IRedisFactory) {
				throw new \Exception('IRedisFactory not registered');
			}

			return $factory->createClient();
		});
		$host->container()->registerAlias(IRedisClientsManager::class, 'IRedisClientsManager');

		$host->container()->register('AppSettingsProviderInterface', function(IContainer $ioc) {
			$factory = $ioc->get(IRedisFactory::class);

			return new RedisAppSettingsProvider($factory->createClient());
		});

		$host->container()->register('AppSettingsInterface', function(IContainer $ioc) {
	      $provider = $ioc->get('AppSettingsProviderInterface');
	      return new AppSettings($provider, HostProvider::instance()->config());
	    });

	}

	/**
	 * Requirements the plugin needs to be executed
	 */
	public function assertGlobalEnvironment()
	{
		if(!extension_loaded('redis')) {
		    throw new \Exception('RedisPlugin required the redis extension.');
		}
	}
}
