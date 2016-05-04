<?hh

namespace Pi\Host;

use Pi\Route,
    Pi\EventManager,
    Pi\Events,
    Pi\Host\OperationDriver,
    Pi\Common\Mapping\AbstractMetadataFactory,
    Pi\Odm\Interfaces\IEntityMetaDataFactory,
    Pi\Interfaces\IRequest,
    Pi\Interfaces\ServiceMetadataInterface,
    Pi\Interfaces\IResponse,
    Pi\Interfaces\IService,
    Pi\Interfaces\ICacheProvider,
    Pi\Interfaces\DtoMetadataInterface,
    Pi\Interfaces\ILog;




/**
 * Services Metadata manager
 */
class ServiceMetadata extends AbstractMetadataFactory implements ServiceMetadataInterface {

  /**
   * Cache key for Operations
   */
  const string CACHE_METADATA_KEY = 'metadata::operation';

  /**
   * Cache key for Operations map
   */
  const string CACHE_METADATA_OPERATION = 'metadata::operations';

  /**
   * Cache key for Services
   */
  const string CACHE_METADATA_SERVICE = 'metadata::service';

  /**
   * Cache key for Request
   */
  const string CACHE_METADATA_REQUEST = 'metadata::request';

  /**
   * Cache key fro response
   */
  const string CACHE_METADATA_RESPONSE = 'metadata::response';

  protected $operationsMap;

  protected Map<string,string> $operationsResponseMap;

  protected Map<string,string> $operationsNameMap;

  protected Vector $requestTypes;

  protected Vector $serviceTypes;

  protected Vector $responseTypes;

  //protected $operations;

  protected OperationMetaFactory $operationFactory;

  private Map<string,string> $loadedMetadata;

  private $initialized = false;

  public function __construct(
    protected RoutesManager $routes,
    protected EventManager $eventManager,
    protected OperationDriver $mappingDriver,
    protected ICacheProvider $cacheProvider,
    protected ILog $log,
    ) 
  {
    parent::__construct($cacheProvider, $eventManager, $mappingDriver);
    $this->serviceTypes = Vector{};
    
    $this->requestTypes = Vector{};
    $this->responseTypes = Vector{};
    $this->operationsResponseMap = Map{};
    $this->operationsMap = Map{};
    $this->operationsNameMap = Map{};
    $this->loadMetadata = Map{};
    $this->loadedMetadata = Map{};
    //$cache = $this->cacheProvider->getAsArray(self::CACHE_METADATA_KEY);
   // $this->operationsMap = unserialize($cache);
  }

  public function doLoadMetadata(DtoMetadataInterface $class)
  {

    try {
      if($class instanceof ServiceMeta) {
          $this->mappingDriver->loadMetadataForClass($class->getName(), $class);  

      } else {
          $this->mappingDriver->loadMetadataForOperation($class->getName(), $class);  
      }
      
    }
    catch(\Exception $ex){
      throw $ex;
    }
    /*if($this->eventManager->has(Events::LoadClassMetadata)){
      $args = new LoadClassMetadataEventArgs($class, $this->documentManager);
      $this->eventManager->dispatch(Events::LoadClassMetadata, $args);
    }*/
  }

  public function getMetadataForService(string $className)
  {
    if($this->loadedMetadata->contains($className)){
      return $this->loadedMetadata->get($className);
    }

    return $this->loadFromCached($className) ?: $this->loadMetadataForService($className);
  }


  protected function loadMetadataForService(string $name)
  {
    if ( ! $this->initialized) {
        $this->initialize();
    }

    $loaded = array();

    $visited = array();

    $className = $name;

    $class = new ServiceMeta($className);


    $this->doLoadMetadata($class);
    $this->setMetadataFor($className, $class);

    $loaded[] = $className;
    
    $this->cache($class);

    return $class;
  }

  public function getOperationMetadata(string $className) : Operation
  {
    return $this->getMetadataFor(ltrim($className, '\\'));
  }

  public function getRequestTypes() : Vector
  {
    return $this->requestTypes;
  }

  public function getServicesTypes() : Vector
  {
    return $this->serviceTypes;
  }

  /**
   * Add a list of Metadata
   * <code>
   * <?hh 
   * $metadata->addAll(array(
   *   array(ExampleService::class, ExampleRequest::class, ExampleResponse::class),
   *   array(ExampleService::class, ExampleRequest::class, ExampleResponse::class)
   * ));
   * @param array $list [description]
   */
  public function addAll(array $list)
  {
    foreach ($list as $val) {
      $this->add($val[0], $val[1], $val[2]);
    }
  }


  /**
   * Add a metadata record
   * @param string      $serviceType  Service class name
   * @param string      $requestType  Request class name
   * @param string|null $responseType Response class name
   */
  public function add(string $serviceType, string $requestType, string $responseType = null) : void
  {
    $this->serviceTypes->add($serviceType);
    $this->requestTypes->add($requestType);
    
    $r = !is_string($requestType) ? get_class($requestType) : $requestType;
    $operation = new Operation($r);
    $operation->serviceType($serviceType);
    $operation->requestType($requestType);
    $operation->responseType($responseType);

    $this->operationsMap[$requestType] = $operation;
    $this->operationsNameMap[strtolower($operation->name())] = $operation;
    if($responseType !== null) {
      $this->responseTypes->add($responseType);
      $this->operationsResponseMap[$responseType] = $operation;
    }

    return $operation;
  }

  /**
   * Return all actions (the service class methods) that implements the requestType
   * The same requestType may be bounded to a GET, POST, PUT, DELETE and ANY
   * @param IService $serviceType [description]
   * @param [type] $requestType [description]
   */
  public function getImplementedActions($serviceType, $requestType) : mixed
  {
    if(!$serviceType instanceof IService){
      throw new \Exception('Service dont implement IService');
    }
  }

  public function getOperationType(string $operationTypeName) : ?string
  {
    $name = strtolower($operationTypeName);
    if($this->operationsNameMap->contains($name)){
      return $this->operationsNameMap[$name]->requestType();
    }
    return null;
  }

  public static function createActionName(string $svcType, string $classMethodName)
  {
    return $svcType.'::'.$classMethodName;
  }

  public function getOperation(string $operationType) : Operation
  {
    if($this->operationsMap->contains($operationType)) {
      return $this->operationsMap[$operationType];  
    }
    
    $operation = $this->getMetadataFor($operationType);
    $route = $this->routes->getByRequest($operationType);
    $svcType = $route->serviceType();
    $reqType = $route->requestType();
    $pattern = $route->pattern();
    
    $operation->serviceType($svcType);
    $operation->requestType($reqType);
    $operation->routes(array($pattern));
    $svcMetadata = $this->getMetadataForService($svcType);
    $details = $svcMetadata->mappings()[$reqType];
    $operation->actions(array($details['method']));

    //$operation->id(ServiceMetadata::createActionName($svcType, $reqType));
    
    return $this->operationsMap[$operationType] = $operation;
  }

  public function getServiceTypeByRequest(string $requestType) : ?string
  {
    if($this->operationsMap->contains($requestType)) {
      return $this->operationsMap[$requestType]->serviceType();
    }

    
  }

  public function getResponseTypeByRequest(string $requestType) : ?string
  {
    if($this->operationsMap->contains($requestType)) {
      return $this->operationsMap[$requestType]->responseType();
    }
  }
  public function initialize()
  {

  }

  public function newEntityMetadataInstance(string $documentName)
  {
    return new Operation($documentName);
  }

  /**
   * Build the ServiceMetada object
   * 
   * @return void
   */
  public function build() : void
  {
    $operations = array();
    $opCounter = 0;
    if(is_array($this->routes->routes)) {
      foreach($this->routes->routes as $route) {
        if($this->operationsMap->contains($route->requestType()) === false){
          $serviceType = $route->serviceType();
          $requestType = $route->requestType();
          $operations[] = $operation =  $this->add($serviceType, $requestType, null);
          //$this->operationsMap[$route->requestType()]->routes()->add($route);
          $cacheKey = self::CACHE_METADATA_KEY.$route->pattern();
          $this->cacheProvider->set($cacheKey, $operation);
          $opCounter++;
        }
      }  
    }
    

    $this->log->debug("Cached $opCounter Operations");
    $this->cacheProvider->set(self::CACHE_METADATA_SERVICE, $this->serviceTypes);
    $this->cacheProvider->set(self::CACHE_METADATA_REQUEST, $this->requestTypes);
    $this->cacheProvider->set(self::CACHE_METADATA_RESPONSE, $this->responseTypes);
    $this->cacheProvider->set(self::CACHE_METADATA_OPERATION, $this->operationsNameMap); 
  }

  /**
   * Read or get an operation map from cache
   * @param  string $key [description]
   * @return [type]      [description]
   */
  public function readOrGetOperationMap(string $key) : ?string
  {
    return $this->operationsMap[$key] ?: $this->operationsMap[$key] = $this->cacheProvider->get(self::CACHE_METADATA_KEY.$key);
  }
}
