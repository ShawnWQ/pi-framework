<?hh

namespace Pi\Host;

use Pi\PiHost,
    Pi\Route,
    Pi\Interfaces\IRoutesManager,
    Pi\Interfaces\BuildInterface,
    Pi\Interfaces\ICacheProvider,
    Pi\Interfaces\ILog;




/**
 * Routes Manager is responsible for handling Routes
 * When built and added, Routes are cached
 * When initialized, the RoutesManager loads all routes
 */
class RoutesManager implements IRoutesManager {
  
  protected ICacheProvider $cacheProvider;

  public ILog $log;

  public array<Route> $routes = array();

  const CACHE_KEY = 'pi::routes::';

  /**
   * @param IPiHost $appHost The current AppHost
   */
  public function __construct(protected PiHost $appHost)
  {
    $this->routes = array();
  }

  /**
   * Creates a unique identifier for Route parts
   * @param  string $method  HTTP Method
   * @param  string $pattern URL pattern
   * @return string Cache Parts
   */
  public static function getCacheParts(string $method = 'GET', string $pattern) : string
  {
    return $method.$pattern;
  }

  public function init()
  {
    $this->log = $this->appHost->logFactory->getLogger(RoutesManager::class);
    $this->cacheProvider = $this->appHost->cacheProvider();
    $this->routes = $this->cacheProvider->get(self::CACHE_KEY);
  }

  public function build()
  {
    $this->cacheProvider->set(self::CACHE_KEY, $this->routes);
    if(count($this->routes) > 0) {
      $this->log->debug(sprintf('Cached %s Routes', count($this->routes)));  
    }
    
  }

  /**
   * Create a new Route object
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

    $this->routes[$requestType] = $route;
    
    return $this;
  }

  public function routes()
  {
    return $this->routes;
  }

  public function setRoutes($rest)
  {
      $this->routes = $rest;
  }

  public function getByRequest(string $requestType)
  {
    return $this->routes[$requestType];
  }

  public function get($restPath, $httpMethod = null)
  {    
    foreach($this->routes as $route){
      
      if($route->matches($restPath, $httpMethod)) {
        return $route;
      }
    }
    return null;
  }

  private function hasExisingRoute($requestType, $restPath)
  {
    foreach($this->routes as $route){
      if($route->matches($requestType)){
        return $route;
      }
    }
  }

  public function dispose()
  {
    unset($this->routes);
  }
}