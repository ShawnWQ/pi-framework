<?hh

namespace Pi\Redis;
use Pi\Interfaces\IPlugin;
use Pi\Interfaces\IPiHost;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\IHasGlobalAssertion;

class SitemapPlugin implements IPlugin {

	public function register(IPiHost $host) : void
	{

		
	}

	/**
	 * Requirements the plugin needs to be executed
	 */
	public function assertGlobalEnvironment()
	{
		
	}
}
