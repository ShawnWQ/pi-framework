<?hh

namespace Pi\Interfaces;

/**
 * Custom logic after plugins are registered
 */
interface IPostInitPlugin {
	public function afterPluginsLoaded(IPiHost $appHost) : void;
}
