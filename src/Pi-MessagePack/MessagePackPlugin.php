<?hh

namespace Pi\MessagePack;

use Pi\Interfaces\IPreInitPlugin;
use Pi\Interfaces\IPiHost;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\IPlugin;
use Pi\Interfaces\IHasGlobalAssertion;

class MessagePackPlugin implements IPlugin, IHasGlobalAssertion {

	public function configure(IPiHost $appHost)
	{
		$this->assertGlobalEnvironment();

		$appHost->container()->register('IServiceSerializer', function(IContainer $ioc){
			return new MessagePackService();
		});
	}

	/**
	 * Requirements the plugin needs to be executed
	 */
	public function assertGlobalEnvironment()
	{
		if(!extension_loaded('msgpack')) {
		    //throw new \Exception('Extension not loaded msgpack.' . PHP_SHLIB_SUFFIX);
		}
	}
}
