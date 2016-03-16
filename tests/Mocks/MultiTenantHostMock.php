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
      		$this->registerService(BibleTestService::class);
			//$this->registerValidator(new MockEntity(), new MockEntityValidator());
			$container->registerRepository(MockEntity::class, EntityRepository::class);
			$this->registerPlugin(new MessagePackPlugin());
		}
	}
