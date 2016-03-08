<?hh

namespace Pi\Host;
use Pi\Route;
use Pi\Interfaces\IRoutesManager;

/**
 * Manage the Routes
 */
class RoutesManager implements IRoutesManager {
  
  protected $appHost;

  /**
   * @param IPiHost $appHost The current AppHost
   */
  public function __construct($appHost) {
    $this->appHost = $appHost;
  }

/**
 * Create a new Route object and add it to the AppHost $restPaths
 * @param [type] $restPath    [description]
 * @param [type] $serviceType [description]
 * @param [type] $requestType [description]
 * @param array  $verbs       [description]
 * @param [type] $summary     [description]
 * @param [type] $notes       [description]
 */
  public function add($restPath, $serviceType, $requestType, array $verbs = array('GET'), $summary = null, $notes = null)
  {
    $route = new Route($restPath, $serviceType, $requestType, true, $verbs);

    $this->appHost->restPaths[$requestType] = $route;

    return $this;
  }

  public function routes()
  {
    return $this->appHost->restPaths;
  }

    public function setRoutes($rest)
    {
        $this->appHost->restPaths = $rest;
    }
  public function getByRequest(string $requestType)
  {
    return $this->appHost->restPaths[$requestType];
  }
  public function get($restPath, $httpMethod = null)
  {    
    foreach($this->appHost->restPaths as $route){
      
      if($route->matches($restPath, $httpMethod)) {
        return $route;
      }
    }
    return null;
  }

  private function hasExisingRoute($requestType, $restPath)
  {
    foreach($this->appHost->restPaths as $route){
      if($route->matches($requestType)){
        return $route;
      }
    }
  }
}
