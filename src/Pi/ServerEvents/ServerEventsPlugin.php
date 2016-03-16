<?hh
namespace Pi\ServerEvents;

use Pi\Interfaces\IPlugin;
use Pi\Interfaces\IPiHost;
use Pi\ServerEvents\ServiceInterface\ServerEventsSubscribersService;
use Pi\ServerEvents\ServiceInterface\ServerEventsUnRegisterService;

class ServerEventsPlugin implements IPlugin {

  public function __construct(protected ?ServerEventsConfiguration $config = null)
  {
    if($config === null){
      $config = new ServerEventsConfiguration();
      $config->streamPath('/event-stream');
      $config->heartbeatPath('/event-heartbeat');
      $config->unRegisterPath('/event-unregister');
      $config->subscribersPath('/event-subscribers');
      $config->limitToAuthenticatedUsers(false);
      $config->notifyChannelOfSubscriptions(true);
    }
  }
  public function register(IPiHost $host)
  {
    $host->registerService(ServerEventsSubscribersService::class);
    $host->registerService(ServerEventsUnRegisterService::class);

    $host->container()->register('IServerEvents', function(IContainer $ioc){
      return new InMemoryServerEvents();
    });
    $host->container()->registerAlias('Pi\ServerEvents\Interfaces\IServerEvents', 'IServerEvents');
  }
}
