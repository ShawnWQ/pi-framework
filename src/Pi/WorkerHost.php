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


abstract class WorkerHost
  extends PiHost {

    protected $appId;

    /**
     * HHVM extensions required by plugins and also the core
     * Extension name - minimal version
     */
    protected Map<string, string> $requiredHhvmExtensions = Set{};
    
    protected $viewEngines;


    public function __construct(protected HostConfig $config = null)
    {
      parent::__construct($config);
    }

    protected function resolveAppId()
    {

    }

    public function getAppId()
    {
      return $this->appId;
    }

  	public function afterInit()
    {
      $route = $this->routes()->get($uri, $method);
      $dto = $this->mapRouteDto($route);


      $httpRequest = new PhpRequest($dto);
      $httpResponse = new PhpResponse();

      foreach (HostProvider::catchAllHandlers() as $key => $handlerFn) {
        $handler = $handlerFn($method, $uri, $uri);
        if($handler != null) {
          $handler->processRequestAsync($httpRequest, $httpResponse, $route->requestType());
          break;
        }
      }
    }
  }
