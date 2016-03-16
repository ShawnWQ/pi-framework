<?hh

namespace Pi\Host;

use Pi\Extensions,
    Pi\EventManager,
    Pi\FileSystem\FileGet,
    Pi\NotImplementedException,
    Pi\Interfaces\IRoutesManager,
    Pi\Interfaces\IRequest,
    Pi\Interfaces\IService,
    Pi\Interfaces\IMessage,
    Pi\Interfaces\ILog,
    Pi\Interfaces\IPiHost,
    Pi\Host\RoutesManager,
    Pi\Host\BasicRequest,
    Pi\Route,
    Pi\Service,
    Pi\Interfaces\IServiceBase,
    Pi\Host\ServiceMeta,
    Pi\Interfaces\IHasFactory,
    Pi\Logging\LogManager,
    Pi\Interfaces\IRequiresRequest,
    Pi\Interfaces\IMessageFactory,
    Pi\Interfaces\IReturn,
    Pi\Interfaces\IEventSubscriber,
    Pi\ServiceModel\NotFoundRequest,
    Pi\ServiceInterface\NotFoundService,
    Pi\Host\Handlers\RestHandler,
    Pi\Host\Handlers\HandlerAttribute,
    Pi\Host\Handlers\NotFoundHandler,
    Pi\Host\Handlers\AbstractPiHandler,
    Pi\Host\Handlers\FileSystemHandler,
    SuperClosure\Serializer,
    SuperClosure\Analyzer\AstAnalyzer,
    SuperClosure\Exception\ClosureAnalysisException;




/**
 * The Service Controler is responsible for executing services
 * Services are registered in IOC container
 */

class ServiceController {

  const CACHE_SERVICECONTROLLER_KEY = 'servicecontroller';

  const CACHE_SERVICECONTROLLER_SERVICES = 'services';

  const CACHE_SERVICECONTROLLER_ROUTES = 'routes';

    /**
   * @var Map
   */
  protected $servicesExecutors = Map<TRequest, TServiceExecuteFn> {};
    /**
   * @var ILog
   */
  private $log;
  /**
   *  A dictionary like for <RequestType, ServiceType>
   */
  protected $services;

  /**
   * @var IMessageFactory
   */
  protected $messageFactory;

  /**
   * @var IPiHost
   */
  protected $appHost;

  protected $servicesR;

  protected $cacheProvider;

  public function __construct(&$appHost)
  {
      $this->appHost = $appHost;
      $this->cacheProvider = $appHost->cacheProvider();
  }

  public function reset()
  {
     $this->servicesR = array();
     $this->services = Map{};
  }  

  /**
   * Initialialize the ServiceController
   * At this point the Applicaion has already registered all services
   */
  public function init()
  {
    $eventManager = $this->appHost->container()->get(EventManager::class);
      $this->hydratorFactory = new OperationHydratorFactory(
        $this->appHost->config(),
        $this->appHost->metadata(),
        $eventManager,
        $this
    );

    $this->messageFactory = $this->appHost->resolve(IMessageFactory::class);
    if(is_null($this->messageFactory)){
      throw new \Exception('A Message Factory should be registered before ServiceController init is called');
    }
    $this->log = LogManager::getLogger(get_class($this));
    $this->appHost->log()->debug(
      sprintf('Initialiazing ServiceController for PiHost %s', $this->appHost->getName())
    );


    // appHost.container.registerAutoWired from appHost.Metadata.ServiceTypes

    // register services from cache
    $provider = $this->appHost->cacheProvider();
    if(is_null($provider)){
      throw new \Exception(
        sprintf('The host hasnt any cache provider configured. ServiceController requires cacheProvider to be set a init method')
      );
    }
    $this->loadFromCache();
    $this->registerService(NotFoundService::class);
    return $this;
  }

  public function build()
  {
     //$this->doRegisterServices();
     $this->registerCache($this->appHost->restPaths, $this->servicesR);
  }

  public function registerCache($routes, $services)
  {
    $obj = array(
      self::CACHE_SERVICECONTROLLER_ROUTES => $routes,
      self::CACHE_SERVICECONTROLLER_SERVICES => $services
    );
    $this->cacheProvider->set(self::CACHE_SERVICECONTROLLER_KEY, $obj);
  }


  protected function loadFromCache()
  {

      $obj = $this->cacheProvider->get(self::CACHE_SERVICECONTROLLER_KEY);

      if($obj == null) {
        $this->log->debug('Cache not loaded.');
        return;
      }

      $this->servicesR = $obj[self::CACHE_SERVICECONTROLLER_SERVICES];
      $this->appHost->routes()->setRoutes($obj[self::CACHE_SERVICECONTROLLER_ROUTES]);
      $this->appHost->metadata()->afterInit();
  }

  public function servicesMap()
  {
    return $this->servicesR;
  }

  /**
   * Register the service meta data information to construct webservices
   * Each meta belongs to a public method
   * @param string $serviceType The service class with namespace
   */
  private function registerServiceMeta(string $serviceType, $requestType, $methodName, $applyTo = null, $version = '0.0.1')
  {
    // The server may be not registered yet
    if(!array_key_exists($serviceType, $this->servicesMeta))
    {
      $this->servicesMeta[$serviceType] = new ServiceMeta($serviceType);
    }
     $this->servicesMeta[$serviceType]->add($requestType, $methodName, $this->reflRequests[$requestType]->getAttributes(), $applyTo , $version = '0.0.1');

  }

  public function getRestPathForRequest($httpMethod, $pathInfo) : string
  {
    return $this->routes()->get($pathInfo, $httpMethod);
  }

  public function servicesMeta()
  {
    return $this->servicesMeta;
  }

  /**
   * Register a Service
   */
  public function registerService(string $className)
  {
    $this->appHost->container->registerAutoWired($className);
    $this->servicesR[] = $className;
    /*
    if(!$instance instanceof Service) {
      return false;
    }
    $serviceType = get_class($instance);
    if(isset($this->servicesR[$serviceType])) return;

    $this->servicesR[$serviceType] = $instance;
    $this->appHost->container->registerInstance($instance);
    if($instance instanceof IEventSubscriber) {
      $this->appHost->eventManager()->add($instance->getEventsSubscribed(), $instance);
    }
*/
  }

  public function registerServiceInstance(mixed $instance)
  {
    $this->appHost->container->registerInstance($instance);
  }

  private function doRegisterServices()
  {

    foreach($this->servicesR as $serviceType) {
      $instance = $this->appHost->container->get($serviceType);
      $instance->setAppHost($this->appHost);
      if(!$instance instanceof IService) {
        throw new \Exception("Invalid service registered $serviceType");
      }

      //$factory = $instance->createInstance();
      $rc = new \ReflectionClass($serviceType);
      $this->reflServices[$serviceType] = $rc;

      $methods = $rc->getMethods();

      foreach($methods as $method)
      {
        $attrs = $method->getAttributes();
        $name = $method->name;
        $params = $rc->getMethod($name)->getParameters();

        if(!is_array($params) || count($params) == 0 || is_null($params[0]->getClass()))
          continue;
        // if not a action service, return

        $requestType = $params[0]->getClass()->getName();
        $this->reflRequests[$requestType] = $method;
        // BUG: aditional interfaces are being registered. filter IService methods only (with Request at firstParamter or having Request attribute)

        if(array_key_exists('Request', $attrs) || array_key_exists('Subscriber', $attrs)) {
          $this->mapRestFromMethod($serviceType, $requestType, $instance, $rc, $method);
          $this->registerServiceInstanceFuck($requestType, $serviceType, $name, $instance);
        }
        if(array_key_exists('Subscriber', $attrs) && is_array($attrs['Subscriber']) && isset($attrs['Subscriber'][0])) {
          $this->appHost->registerSubscriber($attrs['Subscriber'][0], $requestType);
        }
/*
 else {
          $this->mapRestFromDto($serviceType, $requestType, $instance, $rc, $method);
        }*/


      }
    }
  }

  protected function registerServiceInstanceFuck($requestType, $serviceType, $name, $instance)
  {
    $this->addRequestToMap($requestType, $serviceType, $name);
    $this->registerServiceMeta($serviceType, $requestType, $name);
    $this->registerServiceExecutor($requestType, $serviceType, $name, $instance);
  }

  public function getReflRequest(string $requestType)
  {
    return $this->reflRequests[$requestType];
  }

  /*
   * Route is defined at class
   */
  private function mapRestFromDto(string $serviceType, string $requestType, $instance, \ReflectionClass $rc, \ReflectionMethod $method)
  {
    $rc = new \ReflectionClass($requestType);
    $attrs = $rc->getAttributes();

    if(is_array($attrs) && array_key_exists('Route', $attrs)) {

      $this->registerRestPath($serviceType, $requestType, $attrs['Route']);
    }
  }

  /**
   * Request methods identified by Request attribute
   */
  private function mapRestFromMethod(string $serviceType, string $requestType, $instance, \ReflectionClass $rc, \ReflectionMethod $method)
  {
    $name = $method->name;
    $attrs = $method->getAttributes();
    $verbs = array('GET');

    if(array_key_exists('Method', $attrs)){
      $v = $rc->getMethod($name)->getAttribute('Method');

      $verbs = is_array($v) ? $v : array($v);
    } else if(in_array(strtolower($name), array('get', 'post', 'put', 'delete'))){
      $verbs = array(strtoupper($name));

    } else {

    }

    $restPath = '';
    if(array_key_exists('Route', $attrs)){
      $restPath = $rc->getMethod($name)->getAttribute('Route')[0];
    }
    $this->registerRestPath($serviceType, $requestType, $restPath, $verbs);
  }


  public function getReflServiceByRequestType($requestType)
  {

  }

  public function getReflServices()
  {
    return $this->reflServices;
  }

  public function routes()
  {
    return $this->appHost->routes();
  }

  public function registerRestPath(string $serviceType, string $requestType, string $restPath, ?array $verbs = null)
  {

    if(is_null($verbs)){

      $verbs = array('GET');
    }
     $this->routes()->add($restPath, $serviceType, $requestType, $verbs);
     $this->appHost->debug(
        sprintf('Registering the rest path: %s for request type %s', $restPath, $requestType)
    );
  }

  public function registerServiceExecutor(string $requestType, string $serviceType, $method, IHasFactory $factory )
  {

    /*
    * The service executor handler binds context and dto
    * There's always created a new instance. shouldnt be singleton each service as requests/dtos, etc are by reference?
    */
    $reflService = $this->reflServices[$serviceType];

    $this->servicesExecutors[$requestType] = Extensions::protectFn(function(IRequest $context) use($factory, $reflService, $method) {
      $factory->setRequest($context);      
      $factory->setResolver(HostProvider::instance()->container());
      HostProvider::instance()->container()->autoWire($factory);
      return call_user_func(array($factory, $method), $context->dto());
    });
  }
  
  public function protect(\Closure $callable)
  {
      return function () use ($callable) {
          return $callable;
      };
  }

  public function addExecutorToMap(string $requestType, string $serviceType, $method, $handler)
  {
    //$this->servicesExecutors[$requestType] = $this->protect($handler);
    //ServiceExecutor::createExecutionFn($requestType, $serviceType, $method, $handler)
  }

  public function getService($requestType) : ?ServiceExecuteFn
  {
    return $this->servicesExecutors[$requestType];
  }

  public function getServiceInstance(string $serviceName)
  {
    $req = $this->appHost->container->get('IRequest');
    $service = $this->appHost->container->get($serviceName);
    $service->setRequest($req);
    $service->setResolver(HostProvider::instance()->container());
    //HostProvider::instance()->container()->autoWireService($service);
    return $service;
  }

  protected function handleServiceNotRegistered($requestType)
  {
    throw new \Exception(sprintf('The request type %s isnt registered by any Service.', $requestType));
  }

  public function getRequestTypeByOperation($operationName)
  {
    return $this->operations[$operationName];
  }

  public function addRequestToMap(string $requestType, string $serviceType, $methodName)
  {
    $operationName = $requestType;
    $this->services[$requestType] = $serviceType;
    $this->operations[$operationName] = $requestType;
    $this->appHost->log()->debug(
     sprintf('Registering request type %s for service type %s \n\r', $requestType, $serviceType)
   );
  }

  public function executeMessage(IMessage $mqMessage)
	{

	}

  protected function registerExecutor(string $requestType)
  {

  }

  protected function registerServices()
  {

  }

  /**
   *
   * @throws Pi\Validation\ValidationException
   */
  public function execute($requestDto, IRequest $request)
  {

    $requestType = get_class($requestDto);

    if(!isset($this->servicesExecutors[$requestType])) {
      $this->registerExecutor($requestType);
    }
    $instance = $this->servicesExecutors[$requestType];

    $serviceType = $this->services[$requestType];

    $method = $this->servicesMeta[$serviceType]->map()[$requestType]['any']->methodName();

    $context = new \Pi\Host\ActionContext();
    $context->setRequestType($requestType);
    $context->setServiceType($serviceType);

    $context->setServiceAction($instance);
    // get requests and others from apphost or other to $context

    $runner = new ServiceRunner($this->appHost, $context);

    $response = $runner->execute($request, $instance, $requestDto);

    return $response;
  }

  public function executeAsync($requestDto, IRequest $request)
  {
    self::injectRequestDto($request, $requestDto);
    //$requestType -> resulve // set request->operationName from type resolved
    $requestType = "";
    $handlerFn = $this->getService($requestType);


    // return async, response is read as Async
  }

  public function executeWithEmptyRequest($requestDto)
  {
    throw new NotImplementedException();
  }

  public function executeWithCurrentRequest(IRequest $request)
  {
    throw new NotImplementedException();
  }

  /**
   * Inject the IRequest in Service
   */
  static function injectRequestContext($service, IRequest $requestContext)
  {
    if(is_null($requestContext)) return;

    $serviceRequiresContext = $service; // as IRequiresContext
    if($service instanceof IRequiresRequest)

    if(!is_null($serviceRequiresContext))
    {
      $serviceRequiresContext->setRequest($requestContext);
    }
  }

  static function injectRequestDto(IRequest $context, $dto)
  {
    $context->setDto($dto);
  }

  /**
	 *  Execute MQ with requestContext
	 */
  public function executeMessageWithRequest(IMessage $dto, IRequest $requestContext)
  {
    throw new NotImplementedException();
  }

  public function getClassMetadata(string $className)
  {
      return $this->appHost->metadata()->getMetadataFor(ltrim($className, '\\'));
  }
}


/*
 * Create OperationDriver
 * getClassMetadata of MongoManager is provided as well by ServiceController
 */