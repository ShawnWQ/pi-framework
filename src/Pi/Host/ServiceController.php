<?hh

namespace Pi\Host;

use Pi\Extensions;
use Pi\FileSystem\FileGet;
use Pi\NotImplementedException;
use Pi\Interfaces\IRoutesManager;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IService;
use Pi\Interfaces\IMessage;
use Pi\Interfaces\ILog;
use Pi\Interfaces\IPiHost;
use Pi\Host\RoutesManager;
use Pi\Host\BasicRequest;
use Pi\Route;
use Pi\Service;
use Pi\Interfaces\IServiceBase;
use Pi\Host\ServiceMeta;
use Pi\Interfaces\IHasFactory;
use Pi\Logging\LogMannager;
use Pi\Interfaces\IRequiresRequest;
use Pi\Interfaces\IMessageFactory;
use Pi\Interfaces\IReturn;
use Pi\Interfaces\IEventSubscriber;
use Pi\ServiceModel\NotFoundRequest;
use Pi\ServiceInterface\NotFoundService;
use Pi\Host\Handlers\RestHandler;
use Pi\Host\Handlers\HandlerAttribute;
use Pi\Host\Handlers\NotFoundHandler;
use Pi\Host\Handlers\AbstractPiHandler;
use Pi\Host\Handlers\FileSystemHandler;
use SuperClosure\Serializer;
use SuperClosure\Analyzer\AstAnalyzer;
use SuperClosure\Exception\ClosureAnalysisException;

/**
 * The Service Controler is responsible for executing services
 * Services are registered in IOC container
 */

class ServiceController
{


  protected $reflRequests = Map{};

  protected $reflServices = Map{};

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

  /**
   *<RequestType, HandlerFn>
   */
  protected $serviceMapper;

  protected $servicesMeta = Map{};

  /**
   * methods names, temp solution
   * @var [type]
   */
  protected $requests = Set{};

  protected $operations = Map{};

  public function __construct(&$appHost)
  {
      $this->appHost = $appHost;
      $this->services = Map{};
  }

  public function reset()
  {
    
  }  

  /**
   * Initialialize the ServiceController
   * At this point the Applicaion has already registered all services
   */
  public function init()
  {

    $eventManager = $this->appHost->container()->get('EventManager');
      $this->hydratorFactory = new OperationHydratorFactory(
        $this->appHost->config(),
        $this->appHost->metadata(),
        $eventManager,
        $this
    );

    $this->messageFactory = $this->appHost->resolve('IMessageFactory');
    if(is_null($this->messageFactory)){
      throw new \Exception('A Message Factory should be registered before ServiceController init is called');
    }
    $this->log = LogMannager::getLogger(get_class($this));
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
    $this->registerService(new NotFoundService());
    $this->doRegisterServices();

    if(!$this->cacheNotPersisted)
    {
      /*$h = $this->appHost;
      $r = $this->appHost->routes()->routes();
      $e = $this->servicesExecutors;
      $s = $this->servicesR;
      $m = $this->servicesMeta;
      $serialized = array(
        'r' => $r,
        'm' => $m,
          'e' => $e,
          's' => $s
      );

    $sr = serialize($serialized);
      $provider->set('AppHost::Routes', $sr);
*/    }

    $this->appHost->log()->debug('Loading ServiceController routes from cache provider');

    return $this;
  }

  public function build()
  {
     $this->doRegisterServices();
  }

  public function registerCache(array $routes, array $services)
  {

  }

  public function loadCache(array $data)
  {

  }

  protected $cacheNotPersisted = false;

  private function loadFromCache()
  {

    $data = $this->appHost->cacheProvider()->get('AppHost::Routes');

    /*if(!is_null($data)){
      $arr = unserialize($data);
        //$this->appHost->routes()->setRoutes($arr['r']);
        $this->servicesExecutors = $arr['e'];
        $this->servicesR = $arr['s'];
        $this->servicesMeta = $arr['m'];
      //$this->cacheNotPersisted = true;

    }*/
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
  public function registerService(IService $instance)
  {
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

  }

  private function doRegisterServices()
  {

    foreach($this->servicesR as $serviceType => $instance){

      if(!$instance instanceof IService) {
        continue;
      }

      $factory = $instance->createInstance();
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
          $this->registerServiceInstance($requestType, $serviceType, $name, $instance);
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

  protected function registerServiceInstance($requestType, $serviceType, $name, $instance)
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
      $v = $rc->getMethod($name)->getAttribute('Method')[0];
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
      $service = $factory->createInstance();
      $service->setRequest($context);
      
      $service->setResolver(HostProvider::instance()->container());
      
      HostProvider::instance()->container()->autoWireService($service);
      return call_user_func(array($service, $method), $context->dto());
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
    HostProvider::instance()->container()->autoWireService($service);
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

  /**
   *
   * @throws Pi\Validation\ValidationException
   */
  public function execute($requestDto, IRequest $request)
  {

    $requestType = get_class($requestDto);

    if(!isset($this->servicesExecutors[$requestType])) {
      throw new \Exception('The request ' . get_class($requestDto) . ' isnt\'t mapped properly to any service.');
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