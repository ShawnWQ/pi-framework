<?hh

namespace Mocks;

use Mocks\BibleHost;
use Pi\Interfaces\IContainer;
use Pi\Cache\LocalCacheProvider;

class TestHost
	extends BibleHost {

		public function configure(IContainer $container)
		{
			parent::configure($container);
//			$this->registerCacheProvider(new LocalCacheProvider('/tmp/pi-cache.txt'));
		}
	}
