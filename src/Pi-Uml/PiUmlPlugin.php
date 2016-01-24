<?hh

namespace Pi\Uml;
use Pi\Interfaces\IPlugin;
use Pi\Interfaces\IPiHost;


class PiUmlPlugin implements IPlugin {
	
	public function configure(IPiHost $host)	
	{
		$host->registerService(new UmlGeneratorService());
	}

}