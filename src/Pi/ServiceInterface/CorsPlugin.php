<?hh

namespace Pi\ServiceInterface;
use Pi\Interfaces\IPlugin;
use Pi\Interfaces\IPreInitPlugin;
use Pi\Interfaces\IPiHost;

/**
 * CORS Plugin
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS
 */
class CorsPlugin implements IPlugin {

	public function register(IPiHost $host)
	{
		$host->addPreInitRequestFilterclass(new CorsRequestFilter());
	}
}
