<?hh

namespace Pi\Redis;
use Pi\Interfaces\IPlugin;
use Pi\Interfaces\IPiHost;
use Pi\Interfaces\IContainer;
use Pi\Redis\Interfaces\IRedisFactory;
use Pi\Interfaces\IHasGlobalAssertion;
use Pi\Cache\RedisCacheProvider;

class RedisPlugin implements IPlugin {

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
