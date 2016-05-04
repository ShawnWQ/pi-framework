<?hh

namespace Mocks;

use Pi\PhpStaticHost;
use Pi\Interfaces\IContainer;
use Mocks\BibleTestService;
use Pi\MockHost;

class TestAppHost
	extends MockHost {

		public function configure(IContainer $container)
		{
			$this->registerService(new BibleTestService());
		}
	}
