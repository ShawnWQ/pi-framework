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
use Pi\Interfaces\IViewEngine;



abstract class PhpStaticHost
  extends PiHost {

    protected $appId;

    /**
     * HHVM extensions required by plugins and also the core
     * Extension name - minimal version
     */
    protected Map<string, string> $requiredHhvmExtensions = Set{};

    protected $viewEngines;

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

    public function afterInit()
    {
     
      $httpRequest = new PhpRequest(123);
      $httpResponse = new PhpResponse();
      $this->container->register('IRequest', function(IContainer $ioc) use($httpRequest){
        return $httpRequest;
      });
      $this->container->register('IResponse', function(IContainer $ioc) use($httpResponse){
        return $httpResponse;
      });
      $elapsed = new \DateTime('now');
      $this->log->info(
        sprintf("Finalizaing application initialization, took %s ms", $elapsed->format('Y-m-d H:i:s'))
      );
    }
  }
