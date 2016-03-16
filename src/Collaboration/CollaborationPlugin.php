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
		$container->registerRepository('Collaboration\ServiceModel\Types\Meeting', 'Collaboration\ServiceInterface\Data\MeetingRepository');
		$container->registerRepository('Collaboration\ServiceModel\Types\Page', 'Collaboration\ServiceInterface\Data\PageRepository');
		$host->registerService('Collaboration\ServiceInterface\PageService');
		$host->registerService('Collaboration\ServiceInterface\MeetingService');
	}

	/**
	 * Requirements the plugin needs to be executed
	 */
	public function assertGlobalEnvironment()
	{

	}
}
