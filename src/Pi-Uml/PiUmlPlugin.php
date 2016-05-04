<?hh

namespace Pi\Uml;
use Pi\Interfaces\IPlugin;
use Pi\Interfaces\IPiHost;


class PiUmlPlugin implements IPlugin {
	
	public function register(IPiHost $host)	
	{
		$host->registerService(UmlGeneratorService::class);
	}

}