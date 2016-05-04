<?hh

namespace Mocks;

use Mocks\BibleHost;
use Pi\Interfaces\IContainer;
use Pi\Cache\LocalCacheProvider;
use Pi\MessagePack\MessagePackPlugin;
use Warez\ServiceInterface\MovieService;
use Warez\ServiceInterface\SerieService;
use Warez\WarezPlugin;

class WarezHost
	extends BibleHost {

		public function configure(IContainer $container)
		{
			$tmp = __DIR__ .'/../tmp';
      $this->registerPlugin(new WarezPlugin());
		}
	}
