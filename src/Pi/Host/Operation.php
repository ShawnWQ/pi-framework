<?hh

namespace Pi\Host;

use Pi\Extensions,
    Pi\Interfaces\IRequest,
    Pi\Interfaces\DtoMappingMetadataInterface,
    Pi\Common\Mapping\AbstractMetadata;




/**
 * Operation Metadata class
 */
class Operation extends AbstractMetadata {

  protected $multiTenantEnabled = false;
  
  protected $multiTenantField;
  
  protected $serviceType = null;
  
  protected $requestType = null;
  
  protected $responseType = null;
  
  protected $routes = null;
  
  protected $restrictTo = null;
  
  protected $actions = null;

  public function __construct(string $requestName)
  {
    parent::__construct($requestName);
  }

  public function serviceType($value = null)
  {
    if($value === null) return $this->serviceType;
    $this->serviceType = $value;
  }

  public function requestType($value = null)
  {
    if($value === null) return $this->requestType;
    $this->requestType = $value;
  }

  public function responseType($value = null)
  {
    if($value === null) { return $this->responseType; }
    $this->responseType = $value;
  }

  public function routes($value = null)
  {
    if($value === null) return $this->routes;
    $this->routes = $value;
  }

  public function restrictTo($value = null)
  {
    if($value === null) return $this->restrictTo;
    $this->restrictTo = $value;
  }

  public function actions($value = null)
  {
    if($value === null) return $this->actions;
    $this->actions = $value;
  }
  public function name() : ?string
  {
    return $this->name;
  }

  public function isOneWay()
  {
    return $this->responseType == null;
  }

  public function setMultiTenant(bool $enabled)
  {
    $this->multiTenantEnabled = $enabled;
  }

  public function setMultiTenantField(string $fieldName) : void
  {
    $this->multiTenantField = $fieldName;
  }

  public function getMultiTenantField()
  {
    return $this->multiTenantField;
  }

  public function getMultiTenantMode()
  {
    return $this->multiTenantEnabled;
  }

  public function mapFieldArray(array $mapping) : void
  {
    $name = $mapping['name'];
    if($this->reflClass->hasProperty($name)) {
      $reflProp = $this->reflClass->getProperty($name);
      $reflProp->setAccessible(true);
      $this->reflFields[$name] = $reflProp;
    }

    $this->fieldMappings[$name] = $mapping;
  }
}
