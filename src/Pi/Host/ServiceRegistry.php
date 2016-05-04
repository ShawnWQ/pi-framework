<?hh

namespace Pi\Host;

use Pi\PiHost,
	Pi\Extensions,
	Pi\EventManager,
	Pi\Logging\LogManager,
	Pi\Host\OperationHydratorFactory,
	Pi\Host\OperationMetaFactory,
	Pi\Host\OperationDriver,
	Pi\Interfaces\ServiceControllerInterface,
	Pi\Interfaces\IHasFactory,
	Pi\Interfaces\IService,
	Pi\Interfaces\IMessage,
	Pi\Interfaces\IRequest,
	Pi\Interfaces\IResolver,
	Pi\Interfaces\IPiHost,
	Pi\Interfaces\ICacheProvider,
	Pi\Interfaces\IMessageFactory;




class ServiceRegistry implements ServiceControllerInterface, IResolver {

	protected $log;

	/**
	 * The key name used to store ServiceController data
	 */
	const string CACHE_SERVICECONTROLLER_KEY = 'servicecontroller';

	/**
	 * The property of the key used to store ServiceController Services
	 */
  	const string CACHE_SERVICECONTROLLER_SERVICES = 'services';

  	/**
	 * The property of the key used to store ServiceController Routes
	 */
  	const string CACHE_SERVICECONTROLLER_ROUTES = 'routes';

  	/**
  	 * The Cache Provider
  	 * ServiceController register all data on CacheProvider once built
  	 * After built relies on PiHost instance constant ::VERSION
  	 */
	protected ICacheProvider $cacheProvider;

	/**
	 * The Message Factory to manage Messages
	 */
	protected IMessageFactory $messageFactory;

	/**
	 * Hydrator Factory class for Operations (represented by a Request class)
	 * Each Operation contains an Hydrator class generated to extract and set data
	 * The hydration of all objects is done per request, if class not found
	 */
	protected OperationHydratorFactory $hydratorFactory;

	/**
	 * Boolean auxiliar to know if ServiceController was initialized yet
	 */
	protected bool $initialized = false;

	/**
	 * Services Executors already registered by ServiceController
	 */
	protected array $svcExecutors;

	/**
	 * Services class name registered 
	 */
	protected array $svcRegistered;

	public function __construct(protected IPiHost $host)
	{
	
	}

	/**
	 * Injects the IRequest in Service
	 * @param  IService   $service        Service instance
	 * @param  IRequest $requestContext IRequest
	 */
	static function injectRequestContext(IService $service, IRequest $requestContext) : void
	{
		if(is_null($requestContext)){
			 return;
		}
	    
	    $serviceRequiresContext = $service; // as IRequiresContext
	    if($service instanceof IRequiresRequest && !is_null($serviceRequiresContext)) {
	      $serviceRequiresContext->setRequest($requestContext);
	    }
	}

	/**
	 * injects the DTO class in RequestContext
	 * @param IRequest $context RequestContext
	 * @param mixed $dto class instance
	 */
	static function injectRequestDto(IRequest $context, $dto)
	{
		$context->setDto($dto);
	}

	/**
	 * Assert that the ServiceController was already initialized
	 * @throws \InvalidArgumentException If not initialized
	 */
	public function throwIfNotInitialized ()
	{
		if(!$this->initialized) {
			throw new \Exception('The ServiceController should be initialized by now');	
		}
	}

	/**
	 * Try to resolve a dependency from IOC
	 */
	public function tryResolve(string $alias) : ?mixed
	{
		return $this->host->tryResolve($alias);
	}

	public function getClassMetadata(string $className)
	{
		return $this->host->metadata()->getMetadataFor(ltrim($className, '\\'));
	}

	/**
	 * Initialize Service Registry creating the Operation classes
	 * Resolve dependencies and register handles on PiHost
	 */
	public function init()
	{
		$eventManager = $this->host->container()->get(EventManager::class);
      	$this->hydratorFactory = new OperationHydratorFactory(
	        $this->host->config(),
	        $this->host->metadata(),
	        $eventManager,
	        $this
    	);

	    $this->messageFactory = $this->tryResolve(IMessageFactory::class);
	    if($this->messageFactory == null) {
			throw new \Exception('MessageFactory not registered in IOC');
		}
	    $this->log = LogManager::getLogger(get_class($this));
	    $this->log->debug('ServiceController Initialized');
	    $this->initialized = true;
	}

	public function build()
	{
		$this->buildServices();
		$this->registerCache($this->host->cacheProvider(), $this->host->routes()->routes, $this->svcRegistered);
		//$this->log->debug('ServiceController Build');
	}

	/**
	 * Collect all registered Services in the current Application
	 * Build services and requests/responses cache/hydrators
	 */
	protected function buildServices() : void
	{
		foreach ($this->svcRegistered as $svcType) {
			$svcMetadata = $this->host->serviceMetadata->getMetadataForService($svcType);
			
				
			foreach ($svcMetadata->mappings() as $mapping) {
				$requestType = $mapping['name'];
			 	$operation = $this->host->serviceMetadata->getOperation($requestType);
			 	if(is_null($operation)) {
			 		throw new \Exception("Can't get the Operation Metadata for $requestType.");
			 	}
			 	
			 	//$this->host->routes()->add($operation->routes()[0], $operation->serviceType(), $operation->requestType());
			}
		}
		$this->log->debug(count($this->svcRegistered).' services registered.');
	}

	public function registerCache(ICacheProvider $cacheProvider, $routes, $services)
	{
		$obj = array(
	    	//self::CACHE_SERVICECONTROLLER_ROUTES => $routes,
	    	self::CACHE_SERVICECONTROLLER_SERVICES => $services
	  	);
	    $cacheProvider->set(self::CACHE_SERVICECONTROLLER_KEY, $obj);
	    $cacheProvider->set(PiHost::CACHE_VERSION, PiHost::VERSION);
	}

	public function getOperationHydratorFactory() : OperationHydratorFactory
	{
		$this->throwIfNotInitialized();
		return $this->hydratorFactory;
	}

	public function getOperationMetaFactory() : OperationMetaFactory
	{
		$this->throwIfNotInitialized();
		return $this->OperationMetaFactory;
	}

	public function getOperationDriver() : OperationDriver
	{
		$this->throwIfNotInitialized();
	}

	public function getMessageFactory() : IMessageFactory
	{
		$this->throwIfNotInitialized();
		return $this->messageFactory;
	}

	/**
	 * Checks if the Cache Provider contains cache information updated agains the current Pi Host
	 * required by Service Controller like Routes and Services class names
	 * The version is resolved from PiHost constant VERSION, ie: PiHost::VERSION
	 * @return boolean [description]
	 */
	public function isCachedNewer(ICacheProvider $cacheProvider) : bool
	{
		return $cacheProvider->get(PiHost::CACHE_VERSION) == PiHost::VERSION;
	}
	
	/**
	 * Register the given Service
	 * Components like Services, Plugins and Filters are registered per request
	 * A new instance of a Service is only initialized when it's required for a
	 * execution, otherwise itsnt loaded
	 * @param  string $className [description]
	 * @return [type]            [description]
	 */
	public function registerService(string $className) : void
	{
		$this->host->container->registerAutoWired($className);
    	$this->svcRegistered[] = $className;
	}

	/**
	 * Register the given Service Instance
	 * Components like Services, Plugins and Filters are registered per request
	 * A new instance of the given Service is always created
	 * @param  mixed $instance The Service instance
	 */
	public function registerServiceInstance(mixed $instance) : void
  	{
	    $this->host->container->registerInstance($instance);
  	}

	/**
	 * Returns true if a give Service is already registered
	 * @param  string  $className The service class name
	 * @return boolean            True if the service is registered
	 */
	public function hasServiceRegistered(string $className) : bool
	{
		return $this->host->container->hasRegistered($className);
	}

	/**
	 * A Service Executor is a closure responsable for preparing the Service instance 
	 * to execute a request. The instance is already resolved and passed to it (from IOC)
	 * A Service Executor is registered when a request is going to use it
	 * @param  string      $requestType The Request DTO class name
	 * @param  string      $serviceType The Service class name
	 * @param  string      $method      The function method of the current $requestType
	 * @param  IHasFactory $factory     The service instance
	 */
	public function registerServiceExecutor(
		string $requestType, string $serviceType, 
		string $method, IHasFactory $service
	) : void
	{
		if(!$service instanceof IService) {
			throw new \InvalidArgumentException("This service don't implement Pi\Interfaces\IService, instead got ".get_class($service));
		}

	    $this->svcExecutors[$requestType] = Extensions::protectFn(function(IRequest $context) use($service, $method) {
	      $service->setRequest($context);      
	      $service->setResolver(HostProvider::instance()->container());
	      return call_user_func(array($service, $method), $context->dto());
	    });

	}

	/**
	 * Returns true if a give Service Executor was already registered
	 * @param  string  $className The service class name
	 * @return boolean            True if the service executor is registered
	 */
	public function hasServiceExecutor(string $className) : bool
	{
		return isset($this->svcExecutors[$className]);
	}

	
	/**
	 * Execute a Request with or without RequestContext
	 */
	public function execute($dto, ?IRequest $httpReq = null) : mixed
	{
		$requestType = get_class($dto);
	    
	    $serviceType = $this->host->metadata()->getServiceTypeByRequest($requestType);
	    if(empty($serviceType)) {
	    	throw new \InvalidArgumentException("The service type for request $requestType couldn't be resolved.");
	    }
	    
	    $service = $this->getService($serviceType);
	    if(is_null($service)) {
	    	throw new \InvalidArgumentException("Couldn't resolve the service $serviceType for request $requestType");
	    }
	    
	    $context = new \Pi\Host\ActionContext();
	    $context->setRequestType($requestType);
	    $context->setServiceType($serviceType);
	    
	    if(!$this->hasServiceExecutor($requestType)) {
	    	$operation = $this->host->serviceMetadata->getOperation($requestType);
	    	$this->registerServiceExecutor($requestType, $serviceType, $operation->actions()[0], $service);
	    }

	    $executor = $this->svcExecutors[$requestType];
	    $context->setServiceAction($executor);

	    if(is_null($httpReq)) {
	    	$httpReq = new BasicRequest();
	    }

	    $runner = new ServiceRunner($this->host, $context);
	    $response = $runner->execute($httpReq, $executor, $dto);
	    return $response;
	}

	/**
	 * Execute a Request async with or without RequestContext
	 */
	public async function executeAsync($dto, ?IRequest $httpReq = null) : Awaitable<mixed>
	{
		throw new \Exception('NotImplemented');
	}

	/**
	 * Execute a Message with or without RequestContext
	 */
	public function executeMessage(IMessage $message, ?IRequest $httpReq = null) : mixed
	{
		throw new \Exception('NotImplemented');
	}

	/**
	 * Execute a Message async with or without RequestContext
	 */
	public function executeMessageAsync(IMessage $message, ?IRequest $httpReq = null) : Awaitable<mixed>
	{
		throw new \Exception('NotImplemented');
	}

	public function getService(string $className) : IService
	{
		$instance = $this->host->container->get($className);
		return $instance;
	}

}