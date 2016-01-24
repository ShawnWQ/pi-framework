<?hh

namespace Pi;

use Pi\EventManager;
use Pi\Route;
use Pi\Container;
use Pi\Interfaces\IPiHost;
use Pi\Interfaces\IService;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\IMessageFactory;
use Pi\Interfaces\ICacheProvider;
use Pi\Interfaces\IRoutesManager;
use Pi\Interfaces\IPlugin;
use Pi\Interfaces\IPreInitPlugin;
use Pi\Interfaces\IPostInitPlugin;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\Cache\LocalCacheProvider;
use Pi\Cache\InMemoryCacheProvider;
use Pi\Host\HostProvider;
use Pi\Host\ServiceController;
use Pi\Host\ServiceRunner;
use Pi\Host\ActionContext;
use Pi\Host\RoutesManager;
use Pi\Host\BasicRequest;
use Pi\Host\PhpRequest;
use Pi\Host\PhpResponse;
use Pi\Host\ServiceMetadata;
use Pi\Host\Handlers\AbstractPiHandler;
use Pi\Host\Handlers\RestHandler;
use Pi\Host\Handlers\FileSystemHandler;
use Pi\Host\Handlers\NotFoundHandler;
use Pi\Logging\DebugLogFactory;
use Pi\Logging\DebugLogger;
use Pi\Logging\LogMannager;
use Pi\Message\InMemoryService;
use Pi\Message\InMemoryFactory;
use Pi\FileSystem\FileGet;
use Pi\ServiceModel\NotFoundRequest;
use Pi\ServiceModel\DefaultCacheConfig;
use Pi\Common\RandomString;
use Pi\Host\Handlers\StaticHandler;
use Pi\Host\Handlers\HtmlGet;
use Pi\PiHost;

/**
 * Client host for shell
 * @example hhvm cli.php uri=/api/article method=DELETE id=1
 */
abstract class PiCliHost
  extends PiHost {

    protected $appId;

    /**
     * HHVM extensions required by plugins and also the core
     * Extension name - minimal version
     */
    protected Map<string, string> $requiredHhvmExtensions = Set{};

    protected function resolveAppId()
    {

    }

    public function getAppId()
    {
      return $this->appId;
    }


  	public function afterInit()
    {
      array $opts = array();
      foreach ($argv as $arg) {
          $e=explode("=",$arg);
          if(count($e)==2)
              $opts[$e[0]]=$e[1];
          else
              $opts[$e[0]]=0;
      }

      $uri = array_key_exists('uri', $opts) ? explode('?', $opts['uri']) : '/';

      $restPath = explode('?', $uri);
      $uri = $restPath[0];
      $method = array_key_exists('method', $opts) ? explode('?', $opts['method']) : '/';

      $route = $this->routes()->get($uri, $method);
      if(is_null($route)){
        $dto = new NotFoundRequest();
        $action = new ActionContext();
        $action->setServiceType('Pi\ServiceInterface\NotFoundService');
        $action->setRequestType('Pi\ServiceModel\NotFoundRequest');

        $contextRequest = $this->container->get('IRequest');
        $contextRequest->setDto($dto);
        $response = $this->container->get('IResponse');

        //$runner = $this->createServiceRunner($action);

        $handler = $this->getNotFoundHandler();
        $contextRequest = $this->container->get('IRequest');
        $response = $this->container->get('IResponse');
        return $handler->processRequestAsync($contextRequest, $response, $action->getRequestType());

      }

      $dto = $this->mapRouteDto($route);


      $httpResponse = new CliResponse();
      $httpRequest = new CliRequest($httpResponse);

      $this->container->register('IRequest', function(IContainer $ioc) use($httpRequest){
        return $httpRequest;
      });
      $this->container->register('IResponse', function(IContainer $ioc) use($httpResponse){
        return $httpResponse;
      });
      $elapsed = new \DateTime('now');
      $this->log->info(
        sprintf("Finalizaing application took %s ms", $elapsed->format('Y-m-d H:i:s'))
      );
    }
  }
