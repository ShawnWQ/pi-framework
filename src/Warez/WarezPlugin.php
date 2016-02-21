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

		$appHost->registerService(new MovieService());
		$appHost->registerService(new FacebookBotService());
		$appHost->container()->registerRepository(new Movie(), new MovieRepository());
	}

	/**
	 * Requirements the plugin needs to be executed
	 */
	public function assertGlobalEnvironment()
	{

	}
}
