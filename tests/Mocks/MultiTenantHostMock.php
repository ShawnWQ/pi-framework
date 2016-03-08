<?hh

namespace Mocks;

use MultiTenant\MultiTenantMockHost;
use Pi\Interfaces\IContainer;
use Pi\Cache\LocalCacheProvider;
use Pi\MessagePack\MessagePackPlugin;




class MultiTenantHostMock
	extends MultiTenantMockHost {

		public function configure(IContainer $container)
		{
			$tmp = __DIR__ .'/../tmp';
      		$this->registerService(new BibleTestService());
			$this->registerValidator(new MockEntity(), new MockEntityValidator());
			$container->registerRepository(new MockEntity(), new EntityRepository());
			$this->registerPlugin(new MessagePackPlugin());
		}
	}
