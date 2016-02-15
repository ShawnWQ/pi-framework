<?hh

namespace Pi\Redis;

use Pi\AppSettings,
	Pi\Interfaces\IPlugin,
	Pi\Interfaces\IPreInitPlugin,
	Pi\Interfaces\IPiHost,
	Pi\Interfaces\IContainer,
	Pi\Redis\Interfaces\IRedisFactory,
	Pi\Interfaces\IHasGlobalAssertion,
	Pi\Cache\RedisCacheProvider;




class RedisPlugin implements IPlugin, IPreInitPlugin {

	public function configure(IPiHost $host) : void
	{
		$host->container()->register('IRedisFactory', function(IContainer $ioc){
			return new RedisFactory();
		});
		$host->container()->registerAlias('Pi\Redis\Interfaces\IRedisFactory', 'IRedisFactory');

		$host->container()->register('IRedisClientsManager', function(IContainer $ioc){
			$factory = $ioc->get('IRedisFactory');

			if(!$factory instanceof IRedisFactory) {

				throw new \Exception('IRedisFactory not registered');
			}

			return $factory->createClient();
		});

		$redis = $host->container()->get('IRedisClientsManager');
		$host->registerCacheProvider(new RedisCacheProvider($redis));

		$host->container()->registerAlias('Pi\Redis\Interfaces\IRedisClientsManager', 'IRedisClientsManager');

		$host->container()->register('AppSettingsProviderInterface', function(IContainer $ioc) {
			$factory = $ioc->get('IRedisFactory');
			return new RedisAppSettingsProvider($factory->createClient());
		});

		$host->container()->register('AppSettingsInterface', function(IContainer $ioc) {
	      $provider = $ioc->get('AppSettingsProviderInterface');
	      return new AppSettings($provider);
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
