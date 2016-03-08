<?hh

namespace Pi\Host;

/**
 * Wrapper to store all services metadata
 *
 */
class ServiceMeta implements \JsonSerializable  {

  public function jsonSerialize()
  {
    return get_object_vars($this);
  }

  protected $serviceType;

  public function __construct($serviceType)
  {
    $this->serviceType = $serviceType;
    $this->meta = Map{};
    $this->roles = Map{};
  }

  /**
   *
   * @param requestType the request DTO class
   * @param the method called, also know as operation
   * @param apply to verbs
   * @param string $version service version
   */
  public function add($requestType, $methodName, $attributes = null, $applyTo = 'get', $version = '0.0.1')
  {
    $value = new ServiceMetaValue($this->serviceType, $methodName);
    $value->requestType($requestType);
    $this->meta[$requestType] = array($applyTo => $value);
    if($applyTo !== 'any' && !$this->meta->contains('any'))
    {
      $this->meta[$requestType]['any'] = $value;
    }
  }

  public function get($requestType, $verb = 'any')
  {
    return $this->meta[$requestType][$verb];
  }

  public function map()
  {
    return $this->meta;
  }

  /**
   * @var Map
   */
  protected $meta;

  /**
   * @var Map
   */
  protected $roles;
}
