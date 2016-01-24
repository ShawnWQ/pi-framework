<?hh

namespace Pi;

use Pi\Auth\UserRepository,
    Pi\Auth\UserEntity,
    Pi\EventManager,
    Pi\Route,
    Pi\HttpStatusCode,
    Pi\Container,
    Pi\Common\ClassUtils,
    Pi\Redis\RedisPlugin,
    Pi\Auth\AuthPlugin,
    Pi\ServerEvents\ServerEventsPlugin,
    Pi\Interfaces\IPiHost,
    Pi\Interfaces\IService,
    Pi\Interfaces\IContainer,
    Pi\Interfaces\IMessageFactory,
    Pi\Interfaces\ICacheProvider,
    Pi\Interfaces\IRoutesManager,
    Pi\Interfaces\IPlugin,
    Pi\Interfaces\IPreInitPlugin,
    Pi\Interfaces\IPostInitPlugin,
    Pi\Interfaces\IRequest,
    Pi\Interfaces\IResponse,
    Pi\Interfaces\IHasRequestFilter,
    Pi\Interfaces\IHasPreInitFilter,
    Pi\Interfaces\ILog,
    Pi\Interfaces\IFilter,
    Pi\Cache\LocalCacheProvider,
    Pi\Cache\InMemoryCacheProvider,
    Pi\Cache\RedisCacheProvider,
    Pi\Host\HostProvider,
    Pi\Host\OperationDriver,
    Pi\Host\ServiceController,
    Pi\Host\ServiceRunner,
    Pi\Host\ActionContext,
    Pi\Host\RoutesManager,
    Pi\Host\BasicRequest,
    Pi\Host\PhpRequest,
    Pi\Host\PhpResponse,
    Pi\Host\ServiceMetadata,
    Pi\Host\Handlers\AbstractPiHandler,
    Pi\Host\Handlers\RestHandler,
    Pi\Host\Handlers\FileSystemHandler,
    Pi\Host\Handlers\NotFoundHandler,
    Pi\Logging\DebugLogFactory,
    Pi\Logging\DebugLogger,
    Pi\Logging\LogMannager,
    Pi\ServiceModel\DefaultCacheConfig,
    Pi\Message\InMemoryService,
    Pi\Message\InMemoryFactory,
    Pi\FileSystem\FileGet,
    Pi\Odm\OdmPlugin,
    Pi\ServiceModel\ApplicationCreateRequest,
    Pi\ServiceModel\NotFoundRequest,
    Pi\Validation\AbstractValidator,
    Pi\Validation\ValidationPlugin,
    Pi\FileSystem\FileSystemPlugin,
    Pi\ServiceInterface\PiPlugins,
    Pi\ServiceInterface\CorsPlugin,
    Pi\ServiceInterface\Validators\ApplicationCreateValidator,
    Warez\WarezPlugin,
    Pi\Queue\RedisPiQueue,
    Pi\Queue\PiQueue;

/**
 * Base Application Host
 *
 * The role of the Host is to handle all plugins, services, callbacks and everything else that a Service needs.
 * The Services holds the concret dependencies.
 */
abstract class PiHost implements IPiHost{

  const VERSION = '0.0.2-pi';

  /**
   * An IOC container
   * @var Container
   */
  public PiContainer $container;

  /**
   * When the applicaton started
   * @var \DateTime $startedAt
   */
  protected \DateTime $startedAt;

  /**
   * Logger
   * @var ILog $log
   */
  protected $log;

  /**
   * Manage registered routes
   * @var RoutesManager $routes
   */
  protected RoutesManager $routes;

  /**
   * Service Register
   * @var ServiceController $serviceController
   */
  protected ServiceController $serviceController;

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

  protected $metadata;

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

  protected Map<string, IHasPreInitFilter> $preInitRequestFiltersClasses;

  protected Vector<(function(IRequest, IResponse) : void)> $preRequestFilters;

  protected Vector<(function(IRequest, IResponse) : void)> $postRequestFilters;

  protected Vector<(function(IRequest, IResponse) : void)> $onEndRequestCallbacks;

  protected Vector<(function(IRequest, IResponse) : void)> $afterInitCallbacks;

  protected Map<string, (function(IRequest, IResponse, Exception) : void)> $exceptionHandler;

  protected Map<string,mixed> $customErrorHandlers;

  public function __construct(protected HostConfig $config = null)
  {

    ob_start();
    date_default_timezone_set('Europe/Lisbon');

    if($this->config === null){
      $this->config = new HostConfig();
    }

    $path = $this->config->hydratorDir();
    if(!file_exists($path)) {
      mkdir($path);
    }

    HostProvider::configure($this);
    $dir = $dir = $this->config->getHydratorDir();

    /*
     * autoloader for generated files by core framework
     */
    spl_autoload_register(function($class) use($dir) {
        $c = ClassUtils::getClassRealname($class);
        $myclass = $dir . '/' . $c . '.php';
        if (!is_file($myclass)) return false;
        require_once ($myclass);
    });

    $this->restPaths = Map{};
    $this->requestFilters = Vector{};
    $this->globalResponseFilters = Vector{};
    $this->globalRequestFilters = Vector{};
    $this->actionRequestFilters = Vector{};
    $this->actionResponseFilters = Vector{};
    $this->preInitRequestFiltersClasses = Map {};
    $this->requestFiltersClasses = Map{};
    $this->responseFilters = Vector{};
    $this->preRequestFilters = Vector{};
    $this->postRequestFilters = Vector{};
    $this->afterInitCallbacks = Vector{};
    $this->onEndRequestCallbacks = Vector{};
    $this->customErrorHandlers = Map{};

    $factory = new ContainerFactory();
    $this->container = $factory->createContainer();
    $this->startedAt = new \DateTime('now');
    $this->createEventManager();

    $this->serviceController = $this->createServiceController(Set{""});

    // Cache Provider
    if(is_null($this->cacheProvider()))
    {

      $cacheProvider = new InMemoryCacheProvider();
      $this->registerCacheProvider($cacheProvider);
    }

    $driver = OperationDriver::create(array('../'), $this->event, $this->cacheProvider());

    $this->metadata = new ServiceMetadata($this->restPaths, $this->event, $driver, $this->cacheProvider());

    $this->pluginsLoaded = Set{};
    $this->plugins =  array();
    $this->exceptionHandler = Map {};
   // $this->exceptionHandler->add(function(IRequest $request, $dto, \Exception $ex){

    //});
    $this->exceptionHandler->add(Pair {'Pi\UnauthorizedException', function(IRequest $request, IResponse $response, $ex){

      $response->write('Unauthorized Request: ' . get_class($request->dto()), 401);
      $response->endRequest();
      //throw $ex;
    }});
    $this->registerPlugin(new WarezPlugin());
    $this->registerPlugin(new ServerEventsPlugin());
    $this->registerPlugin(new AuthPlugin());
    $this->registerPlugin(new SessionPlugin());
    if(!$this->hasPluginType('Pi\Odm\OdmPlugin')) {
      $this->registerPlugin(new OdmPlugin());
    }
    $this->registerPlugin(new ValidationPlugin());
    $this->registerPlugin(new FileSystemPlugin());
    $this->registerPlugin(new CorsPlugin());
    $this->registerPlugin(new PiPlugins());
    $this->registerPlugin(new RedisPlugin());


    $rm = $this->routes = new RoutesManager($this);
    $this->routes = $rm;
    HostProvider::catchAllHandlers()->add(function(string $httpMethod, string $pathInfo, string $filePath) use($rm){
      $handler = new RestHandler();
      //$httpResponse->headers()->add(Pair{'Content-Type', 'application/json'});
      //$handler->processRequestAsync($httpRequest, $httpResponse, $route->requestType());
      return $handler;
    });

    HostProvider::catchAllHandlers()->add(function(string $httpMethod, string $pathInfo, string $filePath) use($rm){
      return null;
      $this->routes()->get($uri, $method);
      $handler = new FileSystemHandler();
      //$handler->processRequestAsync($httpRequest, $httpResponse, $route->requestType());
      return $handler;
    });

    HostProvider::notFoundHandlers()->add(function(string $httpMethod, string $pathInfo, string $filePath) {

    });

    $this->registerValidator(new ApplicationCreateRequest(), new ApplicationCreateValidator());
    $this->configurePreInitPlugins();


    $this->container()->registerRepository(new UserEntity(), new UserRepository());

    $this->container()->register('Pi\ServiceInterface\OfferCreateBusiness', function(IContainer $ioc){
        $instance = $ioc->createInstance('Pi\ServiceInterface\OfferCreateBusiness');
        $ioc->autoWireService($instance);
        return $instance;
    });

    $this->container()->register('Pi\Interfaces\ILogFactory', function(IContainer $ioc){
      return new DebugLogFactory();
    });

    $factory = $this->container()->get('Pi\Interfaces\ILogFactory');
    $this->log = $factory->getLogger(get_class($this));

    $this->container()->register('Pi\ServiceInterface\UserFriendBusiness', function(IContainer $ioc){
        $instance = $ioc->createInstance('Pi\ServiceInterface\UserFriendBusiness');
        $ioc->autoWireService($instance);
        return $instance;
    });

    $this->container()->register('Pi\ServiceInterface\LikesProvider', function(IContainer $ioc){
      $instance = $ioc->createInstance('Pi\ServiceInterface\LikesProvider');
      $ioc->autoWireService($instance);
      return $instance;
    });

    $this->container()->register('Pi\ServiceInterface\UserFollowBusiness', function(IContainer $ioc){
        $instance = $ioc->createInstance('Pi\ServiceInterface\UserFollowBusiness');
        $ioc->autoWireService($instance);
        return $instance;
    });
    $this->container()->register('Pi\ServiceInterface\UserFeedBusiness', function(IContainer $ioc){
        $instance = $ioc->createInstance('Pi\ServiceInterface\UserFeedBusiness');
        $ioc->autoWireService($instance);
        return $instance;
    });

    $this->container()->register('Pi\Queue\PiQueue', function(IContainer $ioc) {
        $factory = $ioc->get('Pi\Interfaces\ILogFactory');
        $redis = $ioc->get('IRedisClientsManager');
        $logger = $factory->getLogger(PiQueue::NAME);
        return new RedisPiQueue($logger, $redis);
    });

    $this->configure($this->container);
    $this->configurePlugins();
    $this->registerServices();
  }

  public abstract function configure(IContainer $container);

  public abstract function afterInit();

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

  public function build() : void
  {

  }

  public function init() : void
  {

    //$this->routes = new RoutesManager($this);

    $this->container->register('IResponse', function(IContainer $ioc) {

      return new PhpResponse();
    });

    $this->container->register('IRequest', function(IContainer $ioc) {
      $req = new PhpRequest();
      $req->setResponse($ioc->get('IResponse'));
      return $req;
    });



    if(!$this->container->isRegistered('IServiceSerializer')) {
      $this->container->register('IServiceSerializer', function(IContainer $ioc){
        return new PhpSerializerService();
      });
    }

    // Message Service
    $messageFactory = $this->resolve('Pi\Interfaces\IMessageFactory');
    if(is_null($messageFactory)){
      $this->setMessageFactory(new InMemoryFactory());
    }

    // Logger


    if(empty($this->config->getConfigsPath())){
      $this->config->setConfigsPath($_SERVER["DOCUMENT_ROOT"]  . 'config.json');
    }
    if(!file_exists($this->config->getConfigsPath())){

      $file = fopen($this->config->getConfigsPath(), 'w');
      $config = new DefaultCacheConfig();
      $config->setPath($this->config->getConfigsPath());
      fwrite($file, json_encode($config->jsonSerialize()));
      fclose($file);
    }

    // if not built
    $cached = $this->cacheProvider()->get('sa');

    if(is_null($cached)) {
      $this->build();
      $this->serviceController->build();
    }

    $this->serviceController->init();

    // ServiceController initialization requires that dependencies are already configured
    // by apphost and plugins


    // default configs for core plugins (not developed yet)
    $this->delayLoadPlugin = true;
    $this->loadPluginsInternal($this->plugins);

    // plugins may change the specified content type
    $specifiedContentType = 'text/json';
    $this->afterPluginsLoaded($specifiedContentType);
    $this->metadata->afterInit();

    try {
      $this->afterInit();
    }
    catch(\Exception $ex) {
      $this->handleException($ex);
    }
    $httpReq = $this->container->tryResolve('IRequest');
    $httpResponse = $this->container->tryResolve('IResponse');

    if($this->callGlobalRequestFilters($httpReq, $httpResponse)) {
      return;
    }

    $dto = $httpReq->dto();
    if($this->callPreInitRequestFiltersClasses($httpReq, $httpResponse, $dto)) {
      return;
    }



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
        $response->endRequest(false);

  }

  public function setMessageFactory(IMessageFactory $instance) : void
  {
    $this->container->register('IMessageFactory', function(IContainer $container) use($instance) {
      //$instance->ioc($this->container);
      return $instance;
    });

    $this->container->register('IMessageService', function(IContainer $container){
      $factory = $container->get('IMessageFactory');
      $service = new InMemoryService($factory);
      $service->setAppHost($this);

      return $service;
    });
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
    $this->plugins[] = $plugin;  }

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

  public function registerService(IService $service)
  {
    $this->serviceController->registerService($service);
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
    return $this->globalResponseFilters;
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

  public function callAfterInitCallbacks(IRequest $request, IResponse $response)
  {
    if(count($this->afterInitCallbacks) === 0) {
      return false;
    }

    foreach($this->afterInitCallbacks as $k => $fn) {
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
      $this->container()->autoWireService($filter);
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
      $this->container()->autoWireService($filter);
      $filter->execute($request, $response, $dto);

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
    return new ServiceController($this);
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
  }


  /*
   * Later i'll diagnose better what happened here
   * This exceptions are more 99% my fault.
   */
  private function onStartupException(\Exception $ex){
    throw $ex;
  }

  /**
   *
   */
  public function registerCacheProvider(ICacheProvider $instance) : void
  {
    $this->container->remove('ICacheProvider');
    $this->container->register('ICacheProvider', function(IContainer $container) use($instance) {
      $instance->ioc($this->container);
      return $instance;
    });

    $this->container->registerAlias('ICacheProvider', 'Pi\Interfaces\ICacheProvider');
  }

  public function cacheProvider() : ?ICacheProvider
  {
    return $this->container->get('ICacheProvider');
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
    return $this->container->get($dependency);
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

  public function requestFilters()
  {
    return $this->requestFilters;
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

  public function appSettings() : IAppSettings
  {
    throw new NotImplementedException();
  }


  public function log()
  {
    return $this->log;
  }

  public function debug()
  {
    return $this->log->debug(func_get_args()[0]);
  }
   public function metadata()
  {
    return $this->metadata;
  }

  private function createEventManager()
  {
    $this->event = new EventManager();
    $e = $this->event;
    $this->container->register('EventManager', function(IContainer $container) use($e){
      return $e;
    });
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
    return $this->container->getValidator($entity);
  }

  public function registerValidator($entity, AbstractValidator $validator)
  {
    $this->container->registerValidator($entity, $validator);
  }

  /*
   * Plugins
   */

  protected function configurePreInitPlugins()
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
  protected function configurePlugins()
  {
    foreach($this->plugins as $plugin) {

      if($plugin instanceof IPlugin){

        try {
          $plugin->configure($this);
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
        $plugin->configure($this);
        $this->pluginsLoaded->add(get_class($plugin));
      }
      catch(\Exception $ex) {
        $this->onStartupException($ex);
      }
    }
  }
}
