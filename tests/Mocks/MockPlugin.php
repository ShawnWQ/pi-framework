<?hh

namespace Mocks;
use Pi\Interfaces\IPlugin;
use Pi\Interfaces\IPiHost;

class MockPlugin implements IPlugin {

	public function configure(IPiHost $host) : void
	{
		$host->register(new BibleTestService());
	}
}
