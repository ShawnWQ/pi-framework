<?hh

namespace Mocks;

use Pi\MockHost;
use Pi\Interfaces\IContainer;
use Pi\Cache\LocalCacheProvider;
use Pi\MessagePack\MessagePackPlugin,
	SpotEvents\SpotEventsPlugin;

class BibleHost extends MockHost {

	public function configure(IContainer $container)
	{
		$tmp = __DIR__ .'/../tmp';
		$this->registerPlugin(new SpotEventsPlugin());
  		$this->registerService(BibleTestService::class);
//			$this->registerValidator(new MockEntity(), new MockEntityValidator());
		$container->registerRepository(MockEntity::class, EntityRepository::class);
		$this->registerPlugin(new MessagePackPlugin());
	}
}
