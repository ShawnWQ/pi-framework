<?hh

namespace Warez;

use Pi\Interfaces\IPreInitPlugin;
use Pi\Interfaces\IPiHost;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\IPlugin;
use Pi\Interfaces\IHasGlobalAssertion;
use Warez\ServiceInterface\MovieService;
use Warez\ServiceInterface\FacebookBotService;
use Warez\ServiceInterface\Data\MovieRepository;
use Warez\ServiceModel\Types\Movie;

class WarezPlugin implements IPlugin, IHasGlobalAssertion {

	public function register(IPiHost $appHost)
	{
		$this->assertGlobalEnvironment();

		$appHost->registerService(MovieService::class);
		$appHost->registerService(FacebookBotService::class);
		$appHost->container()->registerRepository(Movie::class, MovieRepository::class);
	}

	/**
	 * Requirements the plugin needs to be executed
	 */
	public function assertGlobalEnvironment()
	{

	}
}
