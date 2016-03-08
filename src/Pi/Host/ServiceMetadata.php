<?hh

namespace Pi\Host;
use Pi\Route;
use Pi\EventManager;
use Pi\Host\OperationDriver;
use Pi\Common\Mapping\AbstractMetadataFactory;
use Pi\Odm\Interfaces\IEntityMetaDataFactory;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\Interfaces\IService;
use Pi\Interfaces\ICacheProvider;

/**
 * ATM hacklang doesnt support Maps with object as keys, so i have to keep a side map to get the object by class name in string
 */
class ServiceMetadata extends AbstractMetadataFactory implements IEntityMetaDataFactory {

  protected $operationsMap;

  protected $operationsResponseMap;

  protected $operationsNameMap;

  protected $requestTypes;

  protected $serviceTypes;

  protected $responseTypes;

  protected $operations;

  protected OperationMetaFactory $operationFactory;

  private $loadedMetadata;

  private $initialized = false;

  public function __construct(
    protected &$routes,
    protected EventManager $eventManager,
    protected OperationDriver $mappingDriver,
    protected ICacheProvider $cacheProvider
    ) 
  {
    $this->serviceTypes = Vector{};
    
    $this->requestTypes = Vector{};
    $this->responseTypes = Vector{};
    $this->operationsResponseMap = Map{};
    $this->operationsMap = Map{};
    $this->operationsNameMap = Map{};
    $this->loadMetadata = Map{};
    $this->loadedMetadata = Map{};
  }

  public function getOperationMetadata(string $className)
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

  public function add($serviceType, $requestType, $responseType = null) : void
  {
    $this->serviceTypes->add($serviceType);

    $this->requestTypes->add($requestType);

    //create operation
    
    $r = !is_string($requestType) ? get_class($requestType) : $requestType;
    $operation = new Operation($r);
    $operation->serviceType($serviceType);
    $operation->requestType($requestType);
    $operation->responseType($responseType);


    $this->operationsMap[$requestType] = $operation;
    $this->operationsNameMap[strtolower($operation->name())] = $operation;
    if($responseType !== null) {
      $this->responseTypes->add($responseType);
      $this->operationsResponseMap[get_class($responseType)] = $operation;
    }
  }

  /**
   * Return all actions (the service class methods) that implements the requestType
   * The same requestType may be bounded to a GET, POST, PUT, DELETE and ANY
   * @param IService $serviceType [description]
   * @param [type] $requestType [description]
   */
  public function getImplementedActions($serviceType, $requestType)
  {
    if(!$serviceType instanceof IService){
      throw new \Exception('Service dont implement IService');
    }
  }

  public function getOperationType(string $operationTypeName)
  {
    $name = strtolower($operationTypeName);
    if($this->operationsNameMap->contains($name)){
      return $this->operationsNameMap[$name]->requestType();
    }
    return null;
  }

  public function getOperation($operationType)
  {
    return $this->operationsMap[$operationType];
  }

  public function getServiceTypeByRequest($requestType)
  {
    if($this->operationsMap->contains(get_class($requestType)))

      return $this->operationsMap[get_class($requestType)]->serviceType();

    return null;
  }

  public function getResponseTypeByRequest($requestType)
  {
    if($this->operationsMap->contains(get_class($requestType)))
      return $this->operationsMap[get_class($requestType)]->responseType();
  }

  public function getClassMetadata($className)
  {
    
  }

  public function initialize()
  {

  }

  public function newEntityMetadataInstance(string $documentName)
  {
    return new Operation($documentName);
  }

  public function getRequestMetadata()
  {

  }

  public function doLoadMetadata(Operation $class)
  {
    try {
      $this->mappingDriver->loadMetadataForClass($class->getName(), $class);
    }
    catch(\Exception $ex){
      throw $ex;
    }

  }

  public function getMetadataFor(string $className)
  {
    if($this->loadedMetadata->contains($className)) {
      return $this->loadedMetadata->get($className);
    }

    return $this->loadMetadata($className);
  }

  protected function loadMetadata(string $name)
  {
    if ( ! $this->initialized) {
        $this->initialize();
    }

    $loaded = array();
    $visited = array();
    $className = $name;

    $class = $this->newEntityMetadataInstance($className);
    $this->doLoadMetadata($class);
    $this->setMetadataFor($className, $class);
    $loaded[] = $className;
    return $class;
  }

  public function hasMetadataFor($className)
  {
      return isset($this->loadedMetadata[$className]);
  }

  public function setMetadataFor($className, $class)
  {
    $this->loadedMetadata[$className] = $class;
  }

  public function afterInit() : void
  {
    foreach($this->routes as $route){
      if($this->operationsMap->contains($route->requestType()) === false){
        $this->add($route->serviceType(), $route->requestType(), null);
        //$this->operationsMap[$route->requestType()]->routes()->add($route);
      }
    }
  }
}
