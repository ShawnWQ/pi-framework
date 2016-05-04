<?hh

namespace Pi\Host;
use Pi\Interfaces\IPiHost;
use Pi\Interfaces\IRequest;

class HostProvider {

  protected static  $instance;

  protected static $catchAllHandlers;

  protected static $plugins;

  protected static $notFoundHandlers;

  public static function reset()
  {
    self::$catchAllHandlers = Vector{};
    self::$plugins = Vector{};
    self::$notFoundHandlers = Vector{};
  }

  public static function catchAllHandlers()
  {
    return self::$catchAllHandlers;
  }

  public static function notFoundHandlers()
  {
    return self::$notFoundHandlers;
  }

  public static function plugins()
  {
    return self::$plugins;
  }

  public static function configure(IPiHost $instance)
  {
    self::reset();
    self::$instance = $instance;
  }

  public static function instance() : IPiHost
  {
    return self::$instance;
  }

  public static function tryInstance() : ?IPiHost
  {
    return self::$instance;
  }

  public static function tryResolve($dependency)
  {
  	return self::$instance->tryResolve($dependency);
  }

  public static function debugMode() : bool
  {
    return true;
  }

  public static function assertAppHost()
  {
    if(self::$instance === null || !self::$instance instanceof IPiHost){
      throw new \Exception('AppHost should be registered in HostProvider by now');
    }

    return self::$instance;
  }

  public static function serviceController()
  {
    return self::$instance->serviceController();
  }

  public static function servicesMetadata()
  {
    return self::$instance->metadata();
  }

  public static function metadata()
  {
    return self::$instance->metadata();
  }

  public static function routesManager()
  {
    return self::$instance->routes();
  }

  public static function execute($request, IRequest $httpRequest = null){
    if(is_null($httpRequest)) {
      $httpRequest = self::instance()->tryResolve('IRequest');
    }
      return self::serviceController()->execute($request, $httpRequest);
  }

    
}
