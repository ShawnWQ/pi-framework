<?hh

namespace Mocks;
use Pi\Interfaces\IPlugin;
use Pi\Interfaces\IPiHost;

class MockPlugin implements IPlugin {

	public function register(IPiHost $host) : void
	{
		$host->register(new BibleTestService());
	}
}
