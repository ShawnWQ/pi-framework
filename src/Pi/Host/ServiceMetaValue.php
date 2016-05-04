<?hh

namespace Pi\Host;

class ServiceMetaValue  implements \JsonSerializable {

  public function jsonSerialize()
  {
    return get_object_vars($this);
  }

  protected $serviceType;

  protected $methodName;

  protected $roles = Vector{};

  protected $operationName;

  protected $requestType;

  public function __construct($serviceType, $methodName)
  {
    $this->serviceType = $serviceType;
    $this->methodName = $methodName;
  }

  public function addRole($role)
  {
    $this->roles[] = $role;
  }

  public function serviceType()
  {
    return $this->serviceType;
  }

  public function methodName()
  {
    return $this->methodName;
  }

  public function operationName($value = null)
  {
    if($value === null) return $this->operationName;
    $this->operationName = $value;
  }

  public function roles(?array $roles = null)
  {
    if($roles === null) return $this->roles;
    $this->roles = $roles;
  }

  public function requestType($value = null)
  {
    if($value === null) return $this->requestType;
    $this->requestType = $value;
  }
}
