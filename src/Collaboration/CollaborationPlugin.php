<?hh

namespace Collaboration;


use Collaboration\ServiceInterface\Data\MeetingRepository;
use Collaboration\ServiceInterface\Data\PageRepository;
use Collaboration\ServiceModel\Types\Meeting;
use Collaboration\ServiceModel\Types\Page;
use Collaboration\ServiceInterface\MeetingService;
use Collaboration\ServiceInterface\PageService;
use Pi\Interfaces\IPlugin;
use Pi\Interfaces\IPiHost;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\IHasGlobalAssertion;
use Pi\Cache\RedisCacheProvider;

class CollaborationPlugin implements IPlugin {


	public function register(IPiHost $host) : void
	{

		$container = $host->container();
		$container->registerRepository(new Meeting(), new MeetingRepository());
		$container->registerRepository(new Page(), new PageRepository());
		$host->registerService(new PageService());
		$host->registerService(new MeetingService());
	}

	/**
	 * Requirements the plugin needs to be executed
	 */
	public function assertGlobalEnvironment()
	{

	}
}
