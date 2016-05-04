<?hh

namespace Pi;

use Pi\Auth\UserRepository,
    Pi\Auth\UserEntity,
    Pi\EventManager,
    Pi\Route,
    Pi\HttpStatusCode,
    Pi\Container,
    Pi\Common\ClassUtils,
    Pi\Common\Mapping\Driver\ClassMappingDriver,
    Pi\Common\Mapping\ClassMetadataFactory,
    Pi\Redis\RedisPlugin,
    Pi\Auth\AuthPlugin,
    Pi\Interfaces\ILogFactory,
    Pi\Interfaces\IPiHost,
    Pi\Interfaces\IService,
    Pi\Interfaces\IContainer,
    Pi\Interfaces\AppSettingsInterface,
    Pi\Interfaces\AppSettingsProviderInterface,
    Pi\Interfaces\IMessageFactory,
    Pi\Interfaces\IMessageService,
    Pi\Interfaces\ICacheProvider,
    Pi\Interfaces\IRoutesManager,
    Pi\Interfaces\IPlugin,
    Pi\Interfaces\IPreInitPlugin,
    Pi\Interfaces\IPostInitPlugin,
    Pi\Interfaces\ISerializerService,
    Pi\Interfaces\IRequest,
    Pi\Interfaces\IResponse,
    Pi\Interfaces\IHasRequestFilter,
    Pi\Interfaces\IHasPreInitFilter,
    Pi\Interfaces\ILog,
    Pi\Interfaces\IFilter,
    Pi\Cache\InMemoryCacheProvider,
    Pi\Cache\RedisCacheProvider,
    Pi\Host\HostProvider,
    Pi\Host\OperationDriver,
    Pi\Host\ServiceRegistry,
    Pi\Host\ServiceRunner,
    Pi\Host\ActionContext,
    Pi\Host\RoutesManager,
    Pi\Host\BasicRequest,
    Pi\Host\BasicResponse,
    Pi\Host\PhpResponse,
    Pi\Host\ServiceMetadata,
    Pi\Host\Handlers\AbstractPiHandler,
    Pi\Host\Handlers\RestHandler,
    Pi\Host\Handlers\FileSystemHandler,
    Pi\Host\Handlers\NotFoundHandler,
    Pi\Logging\LogManager,
    Pi\ServiceModel\DefaultCacheConfig,
    Pi\Message\InMemoryService,
    Pi\FileSystem\FileGet,
    Pi\ServiceModel\ApplicationCreateRequest,
    Pi\ServiceModel\NotFoundRequest,
    Pi\Validation\AbstractValidator,
    Pi\ServiceInterface\Validators\ApplicationCreateValidator,
    Pi\Queue\RedisPiQueue,
    Pi\Queue\PiQueue,
    Pi\ServiceInterface\PiPlugins,
    Warez\WarezPlugin,
    SpotEvents\SpotEventsPlugin;




/**
 * Base Application Host
 *
 * The role of the Host is to handle all plugins, services, callbacks and everything else that a Service needs.
 * The Services holds the concret dependencies.
 */
abstract class PiHost implements IPiHost{

  /**
   * Pi Version
   */
  const VERSION = '0.0.6-pi';

  /**
   * Cache key for version
   * The value is used to compare the build 
   */
  const CACHE_VERSION = 'pi::version';

  /**
   * Cache and hydrators folder
   * Cleared at application build by default
   */
  const DYNAMIC_PATH = 'cache';

  /**
   * File name of application configuration file
   */
  const string WEBCONFIG_FILE = 'app.config';

  const string WEBCONFIG_PATH = '';

  /**
   * Settings key for hydrator path
   */
  const string SETTINGS_HYDRATOR_PATH = 'hydrator::';

  /**
   * Settings key for hydrator namespace
   */
  const string SETTINGS_HYDRATOR_NAMESPACE = 'hydrator::namespace::';

  /**
   * Settings key for configuration path
   */
  const string SETTINGS_CONFIG_PATH = 'config::';

  /**
   * Settings key for default logge path
   */
  const string SETTINGS_LOGGER_PATH = 'logger::';

  /**
   * Settings key for default content type
   */
  const string SETTINGS_DEFAULT_CONTENTTYPE = 'ct::';

  /**
   * Settings key fo default protocol
   */
  const string SETTINGS_DEFAULT_PROTOCOL = 'protocol::';

  public static PiHost $instance = null;

  public ILogFactory $logFactory;

  public ICacheProvider $cacheProvider;

  public IMessageFactory $messageFactory;

  /**
   * An IOC container
   * @var Container
   */
  public IContainer $container;

  /**
   * When the applicaton started
   * @var \DateTime $startedAt
   */
  protected float $startedAt;

  /**
   * Logger
   * @var ILog $log
   */
  public $log;

  /**
   * Manage registered routes
   * @var RoutesManager $routes
   */
  public RoutesManager $routes;

  /**
   * Service Register
   * @var ServiceController $serviceController
   */
  public ServiceRegistry $serviceController;

  /**
   * Event provider for subscribe/publish
   * @var EventManager $event
   */
  protected EventManager $event;

  /**
   * Services operations paths
   *
   */
  public $restPaths;

  /**
   * The ServiceMetadata instance
   */
  public ServiceMetadata $serviceMetadata;

  /**
   * Plugins already loaded to the application
   */
  protected $pluginsLoaded;

  /**
   * Plugins registered
   */
  protected  $plugins;

  /**
   * Helper to know when to internally load the plugins
   */
  protected $delayLoadPlugin = false;

  protected Vector<(function(IPiHost) : void)> $afterInitCallbacks;

  /**
   * Request callbacks
   */
  protected Vector<(function(IRequest, IResponse) : void)> $actionRequestFilters;

  protected Vector<(function(IRequest, IResponse) : void)> $actionResponseFilters;

  protected Vector<(function(IRequest, IResponse) : void)> $globalRequestFilters;

  protected Vector<(function(IRequest, IResponse) : void)> $globalResponseFilters;

  protected Vector<(function(IRequest, IResponse) : void)> $requestFilters;

  protected Vector<(function(IRequest, IResponse) : void)> $responseFilters;

  protected Map<string, IHasRequestFilter> $requestFiltersClasses;

  protected Map<string, IHasRequestFilter> $preRequestFiltersClasses;

  protected Map<string, IHasPreInitFilter> $preInitRequestFiltersClasses;

  protected Vector<(function(IRequest, IResponse) : void)> $preRequestFilters;

  protected Vector<(function(IRequest, IResponse) : void)> $postRequestFilters;

  protected Vector<(function(IRequest, IResponse) : void)> $onEndRequestCallbacks;

  protected Map<string, (function(IRequest, IResponse, Exception) : void)> $exceptionHandler;

  protected Map<string,mixed> $customErrorHandlers;

  protected bool $initialized = false;

  protected bool $built = false;

  public function __construct(protected HostConfig $config = null)
  {
    if(!defined('WORK_DIR')) {
      define('WORK_DIR', dirname(__DIR__.'/../../../'));
    }
    $this->startedAt = microtime(true);

    if(!Extensions::testingMode()) {
      ob_start();  
    }

    date_default_timezone_set('Europe/Lisbon');

    if($this->config === null){
      $this->config = new HostConfig();
    }
    
    $this->requestFilters = Vector{};
    $this->globalResponseFilters = Vector{};
    $this->globalRequestFilters = Vector{};
    $this->actionRequestFilters = Vector{};
    $this->actionResponseFilters = Vector{};
    $this->preInitRequestFiltersClasses = Map {};
    $this->preRequestFiltersClasses = Map{};
    $this->requestFiltersClasses = Map{};
    $this->responseFilters = Vector{};
    $this->preRequestFilters = Vector{};
    $this->postRequestFilters = Vector{};
    $this->afterInitCallbacks = Vector{};
    $this->onEndRequestCallbacks = Vector{};
    $this->customErrorHandlers = Map{};
    $this->pluginsLoaded = Set{};
    $this->plugins =  array();
    $this->exceptionHandler = Map {};

    HostProvider::configure($this);

    $factory = new ContainerFactory();
    $container = $this->container = $factory->createContainer();
    $this->routes = new RoutesManager($this);
    $this->serviceController = $this->createServiceController(Set{""});
    $this->createEventManager();
  }

  /**
   * Configure the current application from default
   * Creates the default configuration file
   * @param  AppSettingsProviderInterface
   * @return void
   */
  protected function configureFromDefault(AppSettingsInterface $provider) : void
  {
    $dynamicPath = WORK_DIR.'/'.self::DYNAMIC_PATH;
    $provider->set(self::SETTINGS_HYDRATOR_PATH, $dynamicPath);
    $provider->set(self::SETTINGS_HYDRATOR_NAMESPACE, 'Hydrator');
    $provider->set(self::SETTINGS_CONFIG_PATH, $dynamicPath);
    $provider->set(self::SETTINGS_LOGGER_PATH, $dynamicPath);
    $provider->set(self::SETTINGS_DEFAULT_CONTENTTYPE, "text/json");
    $provider->set(self::SETTINGS_DEFAULT_PROTOCOL, "http");

    $file = <<<EOF
<xml>
  <system.web compilation debug="true" strict="true">
    <assemblies>
      <add assembly="Pi, Version=0.1" />
      <add assembly="Communia", Version="0.1" />
      <add assembly="Pi.Tool, Version=0.1" />
    </assemblies>
  </system.web>
  <customErrors defaultRedirect="url" mode="On">
    <error statusCode="401" redirect="unauthorized"
  </customErrors>
  <httpHandlers>
    <add verb="*" path="*.jpg" type="Pi.Host.Handlers.FileSystemHandler" />
  </httpHandlers>
  <appSettings>
    <add key="Version" value="0.1" />
  </appSettings>
</xml>
EOF;

    file_put_contents(WORK_DIR.'/'.self::WEBCONFIG_PATH.'/'.self::WEBCONFIG_FILE, $file);
  }

  /**
   * Returns the XML Element for the configuration or null if not found
   * @return \SimpleXMLElement
   */
  protected function getConfigXml() : ?\SimpleXMLElement
  {
    try {
      if($file = file_get_contents(self::WEBCONFIG_PATH.'/'.self::WEBCONFIG_FILE)) {
        return new \SimpleXMLElement($file);
      } 
    }
    catch(\Exception $ex) {
      return null;
    }
    

    return null;
  }

  /**
   * Build Cache, Hydrators and other temporary files
   * 
   * Application should be built and then published
   * This method is supposed to be executed only on updates/maintenance
   */
  public function build($clear = true)
  {
    $started = microtime(true);
    $settings = $this->appSettings();

    if($clear) { // Clear all cache file
      $files = glob(WORK_DIR.'/'.self::DYNAMIC_PATH.'/*', GLOB_BRACE); // GLOB_BRACE for hidden files
      foreach ($files as $file) {
        unlink($file);
      }
    }

    // Default directories
    $createDirs = function(array $paths) {
      array_walk($paths, function($path) {
        if(!file_exists(WORK_DIR.'/'.$path)) {
          mkdir(WORK_DIR.'/'.$path);
        }
      });
    };

    $createDirs(array(self::DYNAMIC_PATH));

    // Default configuration
    if(!$config = $this->getConfigXml()) {
      $this->configureFromDefault($settings);
    }
    $this->serviceController->build();
    $this->serviceMetadata->build();
    $this->routes->build();

    $elapsed = (microtime(true) - $started) / 1000; 
    $this->log->debug("Built in $elapsed seconds");
  }

  public abstract function configure(IContainer $container);

  public function preConfigure(IContainer $container)
  {

  }

  public async function registerServices() : Awaitable<void> {
    foreach ($this->plugins as $plugin) {
      $plugins = array();
      if($plugin instanceof IPluginServiceRegister) {
        $plugins[] = $plugin;
      }

      $runner = async function(
        array<WaitHandle<void>> $handles
      ) : Awaitable<void> {
        await AwaitAllWaitHandle::fromArray($handles);
        return array_map($handle ==> $handle->result, $handles);
      };
    }
  }

  public function init()
  {
    $this->exceptionHandler->add(Pair {'Pi\Validation\ValidationException', function(IRequest $request, IResponse $response, $ex){    
      $response->setStatusCode(HttpStatusCode::BadRequest);
      $response->writeDto($request, $ex->getResult());
      $response->endRequest();
    }});
    
    $this->exceptionHandler->add(Pair {'Pi\UnauthorizedException', function(IRequest $request, IResponse $response, $ex){
      $response->write('Unauthorized Request: ' . get_class($request->dto()), 401);
      $response->endRequest();
      if(Extensions::testingMode()) {
        throw $ex;
      }
    }});

    $this->container->registerAlias(ICacheProvider::class, 'ICacheProvider');

    //if(!$this->container->hasRegistered(IServiceSerializer::class)) {
      $this->container->register(ISerializerService::class, function(IContainer $ioc){
        return new PhpSerializerService();
      });
    //}

    /**
     * This implementation is used by all Mapping implementations that don't implement their own IMappingDriver instance
     */
    $this->container->register('ClassMappingDriver', function(IContainer $container){
      $instance = new ClassMappingDriver(
        array(),
        $container->get(EventManager::class),
        $container->get('ICacheProvider'));
      $instance->ioc($container);
      return $instance;
    });

    /**
     * This implementation is used by all Mapping implementations that don't implement their own IEntityMetadataFactory instance
     */
    $this->container->register(ClassMetadataFactory::class, function(IContainer $container){
      $container->get(ICacheProvider::class);
      $instance = new ClassMetadataFactory(
        $container->get(ICacheProvider::class), 
        $container->get(EventManager::class), 
        $container->get('ClassMappingDriver'));
      $instance->ioc($container);
      return $instance;
    });

    $this->container->registerAlias(ClassMetadataFactory::class, 'ClassMetadataFactory');

    HostProvider::catchAllHandlers()->add(function(string $httpMethod, string $pathInfo, string $filePath){
      $handler = new RestHandler();
      return $handler;
    });

    HostProvider::catchAllHandlers()->add(function(string $httpMethod, string $pathInfo, string $filePath) {
      if(RandomString::endsWith($pathInfo, '.html') || RandomString::endsWith($filePath, '.html')) {
        die($pathInfo.$filePath);
      }
    });

    HostProvider::catchAllHandlers()->add(function(string $httpMethod, string $pathInfo, string $filePath){
      return null;
      $this->routes()->get($uri, $method);
      $handler = new FileSystemHandler();
      return $handler;
    });

    HostProvider::notFoundHandlers()->add(function(string $httpMethod, string $pathInfo, string $filePath) {

    });

    $this->registerPlugin(new PiPlugins());

    
    $this->container->registerAlias(ISerializerService::class, 'ISerializerService');

    $this->configure($this->container);

    if(!$this->container->hasRegistered(AppSettingsProviderInterface::class)) {
      $this->container->register(AppSettingsProviderInterface::class, function(IContainer $ioc) {
        return new ApcAppSettingsProvider();
      });
      $this->container->registerAlias(AppSettingsProviderInterface::class, 'AppSettingsProviderInterface');
    }
  
    $this->container->register(AppSettingsInterface::class, function(IContainer $ioc) {
        $provider = $ioc->get(AppSettingsProviderInterface::class);     
        return new AppSettings($provider, HostProvider::instance()->config());
    });
    $this->container->registerAlias(AppSettingsInterface::class, 'AppSettingsInterface');
  

    if(empty($this->config->getConfigsPath())){
      $this->config->setConfigsPath($_SERVER["DOCUMENT_ROOT"]  . 'config.json');
    }
        
    
    $this->log = $this->logFactory->getLogger(get_class($this));

    $this->runPreInitPluginConfiguration();

    //$this->preConfigure($this->container);
    $this->runPluginRegistration();

    //$this->registerServices();
    $hydratorDir = $this->config->getHydratorDir();

    $driver = OperationDriver::create(array('../'), $this->event, $this->cacheProvider());
    $this->serviceMetadata = new ServiceMetadata(
      $this->routes, 
      $this->event, 
      $driver, 
      $this->cacheProvider(), 
      $this->logFactory->getLogger(ServiceMetadata::class)
    );
    /*
     * autoloader for generated files by core framework
     */
    spl_autoload_register(function($class) use($hydratorDir) {
        $c = ClassUtils::getClassRealname($class);
        $myclass = $hydratorDir . '/' . $c . '.php';
        if (!is_file($myclass)) return false;
        require_once ($myclass);
    });

    if(self::$instance != null) {
      throw new \Exception('PiHost.$instance has already been set');
    }
    Service::$globalResolver = self::$instance = $this;

    $this->serviceController->init();
    $this->routes->init();
    
    $this->delayLoadPlugin = true;
    $this->loadPluginsInternal($this->plugins);
    // plugins may change the specified content type
    $this->afterPluginsLoaded('text/json');

    foreach ($this->afterInitCallbacks as $callback) {
      try {
        $callback($this);
      }
      catch(\Exception $ex) {
        $this->onStartupException($ex);
      }
    }
    $took = (microtime(true) - $this->startedAt) / 1000;
    $this->log->debug("PiHost initialized in $took seconds");
    $this->initialized = true;    
    return $this->afterInit();
  }

  public function sanitizeUri() : string
  {
    $uri = $this->getUri();
    $uri = $this->removeQueryParameters($uri);
    $uri = $this->removeTrailSlash($uri); 
    $restPath = explode('?', $uri);
    if(is_array($restPath)) {
      $a = explode($this->config->baseUri(), $restPath[0]);
      if(is_array($a) && count($a) > 1) {
        $uri = $a[1];
      }
    }
    return $uri;
  }

  public function afterInit() : Awaitable<void>
  {
    $uri = $this->sanitizeUri();
    
    $method = $this->getHttpMethod();
    $route = $this->routes()->get($uri, $method);

    if(is_null($route)){
      return $this->notFoundResponse();
    }
    
    $dto = $this->mapRouteDto($route);
    $httpRequest = new BasicRequest();
    $httpResponse = $httpRequest->response();

    $handles = Vector{};
    foreach (HostProvider::catchAllHandlers() as $key => $handlerFn) {
      $handler = $handlerFn($method, $uri, $uri);
      if($handler != null) {
        $handles->add($handler);
        break;
      }
    }
    return $this->executeHandlersAsync($handles);
  }

  protected async function executeHandlersAsync(
    array<WaitHandle<mised>> $handles,
  ): Awaitable<array<mixed>>
  {
    await AwaitAllWaitHandle::fromArray($handles);
    return array_map($handle ==> $handle->result(), $handles);
  }

  protected function notFoundResponse()
  {
    $dto = new NotFoundRequest();
    $action = new ActionContext();
    $action->setServiceType('Pi\ServiceInterface\NotFoundService');
    $action->setRequestType('Pi\ServiceModel\NotFoundRequest');

    $contextRequest = new BasicRequest();
    $contextRequest->setDto($dto);
    $response = $contextRequest->response();
    $handler = $this->getNotFoundHandler();
    return $handler->processRequestAsync($contextRequest, $response, $action->getRequestType());
  }

  public function handleErrorResponse(IRequest $httpReq, IResponse $httpRes, $errorStatus = 200, ?string $errorStatusDescription = null)
  {
    if ($httpRes->isClosed()) return;

    if(!is_null($errorStatusDescription)) {
      $httpRes->setStatusDescription($errorStatusDescription);
    }

    $handler = $this->getCustomErrorHandler($errorStatus);

    if(is_null($handler)) {
      $handler = $this->getNotFoundHandler();
    }

    $handler->processRequest($httpReq, $httpRes, $httpReq->operationName());
  }

  public function getNotFoundHandler()
  {
    if(count($this->customErrorHandlers) > 0) {
      return $this->customErrorHandlers->get(HttpStatusCode::NotFound);
    }

    return new NotFoundHandler();
  }

  public function getCustomErrorHandler(int $statusCode)
  {
    try {
      if(count($this->customErrorHandlers) > 0) {
        return $this->customErrorHandlers->get($statusCode);
      }
    } catch(\Exception $ex) {
      return null;
    }
  }

  public function handleException(\Exception $ex)
  {
    $exType = get_class($ex);
    if($this->exceptionHandler->contains($exType)) {
      $fn = $this->exceptionHandler->get($exType);
      $req = $this->container->tryResolve('IRequest');
      $res = $this->container->tryResolve('IResponse');
      return $fn($req, $res, $ex);
    }
      if(defined('PHPUNIT_PI_DEBUG') === 1) {
          throw $ex;
      }

      $response = $this->tryResolve('IResponse');
      $response->write('<html><head><title>Pi Stacktrace</title></head><body><h1>Error: ' . $ex->getMessage() . '</h1>' . $ex->getTraceAsString() . '</body>', 500);
      $response->endRequest(true);

  }

  protected function getUri()
  {
    return !isset($_SERVER['REQUEST_URI']) ? '/' : $_SERVER['REQUEST_URI'];
  }

  protected function removeTrailSlash(string $uri) : string
  {
      return substr($uri, -1) == '/' ? substr($uri, 0, -1) : $uri;
  }

  protected function removeQueryParameters(string $uri) : string
  {
    $arr = explode('?', $uri);
    return is_array($arr) ? $arr[0] :
      (is_string($arr) ? $arr : '');
  }

    
  protected function getHttpMethod() : string
  {
    return isset($_SERVER['REQUEST_METHOD']) && in_array($_SERVER['REQUEST_METHOD'], array('GET', 'PUT', 'POST', 'DELETE')) ? $_SERVER['REQUEST_METHOD'] : 'GET';
  }

  public function setMessageFactory(IMessageFactory $instance) : void
  {
    $this->messageFactory = $instance;
    $this->container->register(IMessageFactory::class, function(IContainer $container) use($instance) {
      //$instance->ioc($this->container);
      return $instance;
    });
    $this->container->registerAlias(IMessageFactory::class, 'IMessageFactory');
    $this->container->register(IMessageService::class, function(IContainer $container){
      $factory = $container->get(IMessageFactory::class);
      $service = new InMemoryService($factory);
      $service->setAppHost($this);

      return $service;
    });
    $this->container->registerAlias(IMessageService::class, 'IMessageService');
  }

  public function plugins() : array
  {
    return $this->plugins;
  }

  public function hasPlugin(IPlugin $instance) : bool
  {
    foreach($this->plugins as $plugin) {
      if($instance === $plugin) {
        return true;
      }
    }
    return false;
  }

  public function hasPluginType(string $pluginType) : bool
  {
   foreach($this->plugins as $plugin) {
      if(get_class($plugin) === $pluginType) {
        return true;
      }
    }
    return false;
  }

  public function registerPlugin(IPlugin $plugin)
  {
    if($plugin === null) {
      throw new \Exception('Plugin is null');
    }
    HostProvider::plugins()->add($plugin);
    $this->plugins[] = $plugin;
  }

  public function removePlugin(IPlugin $plugin) : bool
  {
    return false;
  }

  public function getPluginsLoaded()
  {
    return $this->pluginsLoaded;
  }



  /**
   * @todo This allow plugins to be loaded in run time checking implementations
   * Not ready yet
   */
  public function loadPlugin(array $plugins) : void
  {
    if($this->delayLoadPlugin) {
      $this->loadPluginsInternal($plugins);
      $this->plugins->add($plugins);
    } else {
      foreach($plugins as $plugin) {
        $this->plugins->add($plugin);
      }
    }
  }

  /**
   * Continues the processing of Response, writting it
   * processResponse will be called with the Response for the current IRequest
   * Others response obtained from the requested service may be returned as BachResponse
   * This method should belong to a new implementation of PiHost which will be concret used for webapps (requests are outputed)
   * Others PiHost wouln'd output the response like a internal message comunication host
   * @param [type] $response [description]
   */
  public function processResponse($response)
  {

    //echo json_encode($response);
  }

  public function createServiceRunner(ActionContext $context)
  {
    return new ServiceRunner($this, $context);
  }

  public function registerService(string $service)
  {
    $this->serviceController->registerService($service);
  }

  public function registerServiceInstance(mixed $instance)
  {
    return $this->serviceController->registerServiceInstance($instance);
  }

  public function actionRequestFilters() : Vector<(function(IRequest, IResponse) : void)>
  {
    return $this->actionRequestFilters;
  }

  public function callActionRequestFilters(IRequest $request, IResponse $response)
  {
    if(count($this->actionRequestFilters) === 0) {
      return false;
    }

    foreach($this->actionRequestFilters as $k => $fn){
      $fn($request, $response);

      if($response->isClosed()){
        break;
      }
    }

    return $response->isClosed();
  }

  public function actionResponsetFilters() : Vector<(function(IRequest, IResponse) : void)>
  {
    return $this->actionResponsetFilters;
  }

  public function callActionResponseFilters(IRequest $request, IResponse $response)
  {
    if(count($this->actionResponseFilters) === 0) {
      return false;
    }

    foreach($this->actionResponseFilters as $k => $fn){
      $fn($request, $response);

      if($response->isClosed()){
        break;
      }
    }

    return $response->isClosed();
  }

  public function requestFilters() : Vector<(function(IRequest, IResponse) : void)>
  {
    return $this->requestFilters;
  }

  public function callRequestFilters($priority = -1, IRequest $request, IResponse $response)
  {
    if(count($this->requestFilters) === 0) {
      return false;
    }

    foreach($this->requestFilters as $k => $fn){
      $fn($request, $response);

      if($response->isClosed()){
        break;
      }
    }

    return $response->isClosed();
  }

  public function responseFilters() : Vector<(function(IRequest, IResponse) : void)>
  {
    return $this->responseFilters;
  }

  public function callResponseFilters($priority = -1, IRequest $request, IResponse $response)
  {
    if(count($this->responseFilters) === 0) {
      return false;
    }

    foreach($this->responseFilters as $k => $fn){
      $fn($request, $response);

      if($response->isClosed()){
        break;
      }
    }

    return $response->isClosed();
  }

  public function globalRequestFilters() : Vector<(function(IRequest, IResponse) : void)>
  {
    return $this->globalRequestFilters;
  }
  
  public function callGlobalRequestFilters(IRequest $request, IResponse $response)
  {
    if(count($this->globalRequestFilters) === 0) {
      return false;
    }

    foreach($this->globalRequestFilters as $fn){
      $fn($request, $response);

      if($response->isClosed()){
        break;
      }
    }

    return $response->isClosed();
  }

  public function globalResponseFilters() : Vector<(function(IRequest, IResponse) : void)>
  {
    return $this->globalResponseFilters;
  }

  public function callGlobalResponseFilters(IRequest $request, IResponse $response)
  {
    if(count($this->globalResponseFilters) === 0) {
      return false;
    }

    foreach($this->globalResponseFilters as $k => $fn){
      $fn($request, $response);

      if($response->isClosed()){
        break;
      }
    }

    return $response->isClosed();
  }

  public function callPostRequestFilters(IRequest $request, IResponse $response)
  {
    if(count($this->postRequestFilters) === 0) {
      return false;
    }

    foreach($this->postRequestFilters as $k => $fn){
      $fn($request, $response);

      if($response->isClosed()){
        break;
      }
    }

    return $response->isClosed();
  }

  public function callOnEndRequest(IRequest $request, IResponse $response)
  {
    if(count($this->onEndRequestCallbacks) === 0) {
      return false;
    }

    foreach($this->onEndRequestCallbacks as $k => $fn) {
      $fn($request, $response);

      if($response->isClosed()){
        break;
      }
    }

    return $response->isClosed();
  }

  public function callPreRequestFilters(IRequest $request, IResponse $response)
  {
    if(count($this->preRequestFilters)) {
      return false;
    }

    foreach($this->preRequestFilters as $k => $fn) {
      $fn($request, $response);

      if($response->isClosed()){
        break;
      }
    }

    return $response->isClosed();
  }

  public function addPreInitRequestFilterclass(IHasPreInitFilter $filter) : void
  {
    $this->preInitRequestFiltersClasses[get_class($filter)] = $filter;
  }

  public function callPreInitRequestFiltersClasses(IRequest $request, IResponse $response, $dto)
  {
    if(count($this->preInitRequestFiltersClasses) === 0) {
      return false;
    }

    foreach($this->preInitRequestFiltersClasses as $key => $filter) {
      $filter->setAppHost($this);
      $this->container->autoWire($filter);
        $filter->execute($request, $response, $dto);

      if($response->isClosed()){
        break;
      }
    }
  }

  public function addRequestFiltersClasses(IHasRequestFilter $filter) : void
  {
    $this->requestFiltersClasses[get_class($filter)] = $filter;
  }

  public function callRequestFiltersClasses(IRequest $request, IResponse $response, $dto)
  {
    if(count($this->requestFiltersClasses) === 0) {
      return false;
    }
    foreach($this->requestFiltersClasses as $key => $filter) {
      $filter->setAppHost($this);
      $this->container->autoWire($filter);
      $filter->execute($request, $response, $dto);

      if($response->isClosed()){
        break;
      }
    }
  }

  public function addPreRequestFilterClass(IHasRequestFilter $filter) : void
  {
    $this->preRequestFiltersClasses[get_class($filter)] = $filter;
  }

  public function callPreRequestFiltersClasses(IRequest $request, IResponse $response)
  {
    if(count($this->preRequestFiltersClasses) === 0) {
      return false;
    }
    foreach($this->preRequestFiltersClasses as $key => $filter) {
      $filter->setAppHost($this);
      $this->container->autoWire($filter);
      $filter->execute($request, $response);

      if($response->isClosed()){
        break;
      }
    }
  }

  public function mapRouteDto(Route $route)
  {
    $type = $route->requestType();
    $rc = new \ReflectionClass($type);

    $request = new $type();
    return $request;
  }

  public function serviceController()
  {
    return $this->serviceController;
  }

  protected function createServiceController(Set $paths)
  {
    return new ServiceRegistry($this);
  }

  public function execute($requestDto, IRequest $request)
  {
    return $this->serviceController->execute($requestDto, $request);
  }

  public function endRequest() : void
  {
    if(!defined('PHPUNIT_PI_DEBUG') === 1) {
      exit(0);
    }
    if(!Extensions::testingMode()) {
      die();
    }
  }


  /*
   * Later i'll diagnose better what happened here
   * This exceptions are more 99% my fault.
   */
  private function onStartupException(\Exception $ex)
  {
    throw $ex;
  }

  /**
   *
   */
  public function registerCacheProvider(string $className) : void
  {
    //$this->container->remove('ICacheProvider');
    $this->container->registerAutoWiredAs($className, ICacheProvider::class);
  }

  public function registerCacheProviderInstance($obj)
  {
    $this->container->registerInstanceAs(ICacheProvider::class, $obj);
  }

  public function cacheProvider() : ?ICacheProvider
  {
    return $this->container->get(ICacheProvider::class);
  }

  /**
   * Register dependency in IOC
   */
  public function register<TDependency>(TDependency $instance, $name = null) : void
  {
    if($name === null)
      $name = get_class($instance);

    $this->container->register($name, function(IContainer $container) use($instance) {
      $instance->ioc($this->container);
      return $instance;
    });
  }

  public function resolve($dependency)
  {
    return $this->container->get($dependency);
  }

  public function tryResolve($dependency)
  {
    return $this->container->tryResolve($dependency);
  }

  /**
   * Calls directly after services and filters are executed.
   */
  public function release($instance) : void
  {

  }

  /**
   * Called at the end of each request
   */
  public function onEndRequest(IRequest $request)
  {

  }

  public function container() : IContainer
  {
    return $this->container;
  }



  /**
   * Routes registered
   */

  public function routes() : IRoutesManager
  {
    return $this->routes;
    //return $this->restPaths;
    //return $this->serviceController->routes();
  }

  public function requestFiltersClasses()
  {
    return $this->requestFiltersClasses;
  }

  public function preRequestFilters()
  {
    return $this->preRequestFilters;
  }

  public function postRequestFilters()
  {
    return $this->postRequestFilters;
  }

  public function afterInitCallbacks()
  {
    return $this->afterInitCallbacks;
  }

  public function onDisposeCallbacks()
  {

  }

  public function config()
  {
    return $this->config;
  }

  public function appSettings() : AppSettingsInterface
  {
    return $this->container->get('AppSettingsInterface');
  }


  public function log()
  {
    return $this->log;
  }

  public function debug()
  {
    return $this->log->debug(func_get_args()[0]);
  }
  
  /**
   * Get Service Metadata class
   * @return ServiceMetadata
   */
  public function metadata() : ServiceMetadata
  {
    return $this->serviceMetadata;
  }

  private function createEventManager()
  {
    $this->event = new EventManager();
    $e = $this->event;
    $this->container->register(EventManager::class, function(IContainer $container) use($e){
      return $e;
    });
    $this->container->registerAlias(EventManager::class, 'EventManager');
  }

  public function registerSubscriber(string $eventName, string $requestType)
  {
    $callable = function($dto) {
      $context = HostProvider::instance()->tryResolve('IRequest');
      return HostProvider::execute($dto, $context);
    };

    $this->event->addTyped($eventName, $requestType, $callable);

  }

  public function eventManager()
  {
    return $this->event;
  }

  public function getName()
  {
    return 'test';
  }

  /**
   * The absolute url for this request
   */
  public function resolveAbsoluteUrl(): string
  {
    return "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  }

  public function getValidator($entity) : ?AbstractValidator
  {
    return $this->container->get(get_class($entity));
  }

  public function registerValidator($entity, AbstractValidator $validator)
  {
    $this->container->registerValidator($entity, $validator);
  }

  /*
   * Plugins
   */

  protected function runPreInitPluginConfiguration()
  {
    foreach($this->plugins as $plugin) {

      if($plugin instanceof IPreInitPlugin){

        try {
          $plugin->configure($this);
        } catch(\Exception $ex){
          return $this->onStartupException($ex);
        }

      }
    }
  }
  protected function runPluginRegistration()
  {
   
    foreach($this->plugins as $plugin) {

      if($plugin instanceof IPlugin){

        try {
          $plugin->register($this);
        }
        catch (\Exception $ex){
          $this->onStartupException($ex);
        }

      }
    }
  }

  /**
   *
   * If content type is inserted, its saved in configuration
   */
  protected function afterPluginsLoaded($contentType)
  {
    if(!empty($contentType)) {
      $this->config->defaultContentType($contentType);
    }

    foreach($this->plugins as $plugin) {
      if($plugin instanceof IPostInitPlugin) {
        try {
          $plugin->afterPluginsLoaded($this);
        }
        catch(\Exception $ex) {
          $this->onStartupException($ex);
        }
      }
    }
  }

  protected function loadPluginsInternal($plugins)
  {
    foreach($this->plugins as $plugin) {
      try {
        $plugin->register($this);
        $this->pluginsLoaded->add(get_class($plugin));
      }
      catch(\Exception $ex) {
        $this->onStartupException($ex);
      }
    }
  }

  public function dispose() : void
  {
    self::$instance = null;

    if($this->container != null) {
      $this->container->dispose();
    }
  }
}
