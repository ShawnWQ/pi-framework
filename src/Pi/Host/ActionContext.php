<?hh

namespace Pi\Host;

/**
 * Context to capture IService action
 */
class ActionContext {
  const Any = 'Any';

  protected $id;

  protected $requestType;

  protected $serviceType;

  /**
   * @param ActionInvokerFn
   */
  protected $serviceAction;

  protected $requestFilters;

  protected $responseFilers;

  public function getServiceType()
  {
    return $this->serviceType;
  }

  public function getRequestType()
  {
    return $this->requestType;
  }

  public function getServiceAction()
  {
    return $this->serviceAction;
  }

  public function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id =$id;
  }

  public function setRequestType($type)
  {
    $this->requestType = $type;
  }

  public function setServiceType($type)
  {
    $this->serviceType = $type;
  }
  public static function key($method, $requestDtoName)
  {
    // $method to upper
    return $method . " " . $requestDtoName;
  }

  public function setServiceAction($handler)
  {
    $this->serviceAction = $handler;
  }

  public static function anyKey($requestDtoName)
  {
    return Any . ' ' . $requestDtoName;
  }
}
