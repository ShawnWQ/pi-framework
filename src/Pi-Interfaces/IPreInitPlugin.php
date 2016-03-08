<?hh

namespace Pi\Interfaces;

/**
 * Custom logic before plugins are registered
 */
interface IPreInitPlugin extends IPlugin {
	//public function register(IPiHost $appHost) : void;
	public function configure(IPiHost $appHost) : void;
}
