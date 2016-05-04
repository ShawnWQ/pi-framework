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
use Pi\Logging\LogManager;
use Pi\Message\InMemoryService;
use Pi\Message\InMemoryFactory;
use Pi\FileSystem\FileGet;
use Pi\ServiceModel\NotFoundRequest;
use Pi\ServiceModel\DefaultCacheConfig;
use Pi\Common\RandomString;
use Pi\Host\Handlers\StaticHandler;
use Pi\Host\Handlers\HtmlGet;




abstract class AppHost extends PiHost {

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
    public function registerViewEngine(IViewEngine $engine)
    {
      $this->viewEngines[] = $engine;
    }

    protected function resolveAppId()
    {

    }

    public function getAppId()
    {
      return $this->appId;
    }

    protected function handleHtmlRequest()
    {

      $contextRequest = $this->container->get('IRequest');

      $dto = new HtmlGet($this->getUri());
      $contextRequest->setDto($dto);

      $response = $this->container->get('IResponse');

      $handler = new StaticHandler($this->config()->staticFolder());
      return $handler->processRequest($contextRequest, $response, get_class($dto));
    }

    

  	public function afterInit()
    {
      $uri = $this->getUri();
      $uri = $this->removeQueryParameters($uri);
      $uri = $this->removeTrailSlash($uri);

      if(RandomString::endsWith($uri, '.html')) {
        return $this->handleHtmlRequest();
      }

      $restPath = explode('?', $uri);
      if(is_array($restPath)) {
        $a = explode($this->config->baseUri(), $restPath[0]);
        if(is_array($a) && count($a) > 1) {
          $uri = $a[1];
        }
      }
      

      $method = $this->getHttpMethod();

      $route = $this->routes()->get($uri, $method);
      if(is_null($route)){
        $dto = new NotFoundRequest();
        $action = new ActionContext();
        $action->setServiceType('Pi\ServiceInterface\NotFoundService');
        $action->setRequestType('Pi\ServiceModel\NotFoundRequest');

        $contextRequest = $this->container->get('IRequest');
        $contextRequest->setDto($dto);
        $response = $this->container->get('IResponse');

        $handler = $this->getNotFoundHandler();
        $contextRequest = $this->container->get('IRequest');
        $response = $this->container->get('IResponse');
        return $handler->processRequestAsync($contextRequest, $response, $action->getRequestType());

      }

      $dto = $this->mapRouteDto($route);
      $httpRequest = $this->container->get('IRequest') ?: new BasicRequest();;
      $httpResponse = $this->container->get('IResponse') ?: $httpRequest->response();

      foreach (HostProvider::catchAllHandlers() as $key => $handlerFn) {
        $handler = $handlerFn($method, $uri, $uri);
        if($handler != null) {
          $handler->processRequestAsync($httpRequest, $httpResponse, $route->requestType());
          break;
        }
      }
    }
  }
