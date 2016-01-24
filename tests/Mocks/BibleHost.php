<?hh

namespace Mocks;

use Pi\MockHost;
use Pi\Interfaces\IContainer;
use Pi\Cache\LocalCacheProvider;
use Pi\MessagePack\MessagePackPlugin;

class BibleHost
	extends MockHost {

		public function configure(IContainer $container)
		{
			$tmp = __DIR__ .'/../tmp';
      		$this->registerService(new BibleTestService());
			$this->registerValidator(new MockEntity(), new MockEntityValidator());
			$container->registerRepository(new MockEntity(), new EntityRepository());
			$this->registerPlugin(new MessagePackPlugin());
		}
	}
