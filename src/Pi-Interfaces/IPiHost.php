<?hh

namespace Pi\Interfaces;
use Pi\Host\ActionContext;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IService;
use Pi\Interfaces\IRoutesManager;
use Pi\Interfaces\IPlugin;
use Pi\Validation\AbstractValidator;


interface IPiHost {

  public function plugins() : array;

  public function build() : void;

  /**
   * @return bool True if the plugin exists and was removed, false if dont exists
   */
  public function removePlugin(IPlugin $plugin) : bool;

  /**
   * Check if the current application has already registered an plugin
   * @return bool True if it's registered
   */
  public function hasPlugin(IPlugin $plugin);

  public function getValidator($entity) : ?AbstractValidator;

  public function registerValidator($entity, AbstractValidator $validator);
  /**
   * Register dependency in IOC
   */
  public function register($instance) : void;

  public function registerService(string $service);

  public function registerSubscriber(string $eventName, string $requestType);

  public function container();

  /**
   * Calls directly after services and filters are executed.
   */
  public function release($instance) : void;

  /**
   * Called at the end of each request
   */
  public function onEndRequest(IRequest $request);

  public function endRequest() : void;

  public function globalRequestFilters() : Vector<(function(IRequest, IResponse) : void)>;

  public function globalResponseFilters() : Vector<(function(IRequest, IResponse) : void)>;

  public function requestFilters() : Vector<(function(IRequest, IResponse) : void)>;

  public function responseFilters() : Vector<(function(IRequest, IResponse) : void)>;

  public function actionRequestFilters() : Vector<(function(IRequest, IResponse) : void)>;

  public function actionResponsetFilters() : Vector<(function(IRequest, IResponse) : void)>;

  public function callGlobalRequestFilters(IRequest $request, IResponse $response);


  public function callGlobalResponseFilters(IRequest $request, IResponse $response);

  public function callRequestFiltersClasses(IRequest $request, IResponse $response, $dto);

  public function addPreInitRequestFilterclass(IHasPreInitFilter $filter) : void;
  /**
   * Routes registered
   */

  public function routes() : IRoutesManager;

  public function preRequestFilters();

  public function afterInitCallbacks();

  public function onDisposeCallbacks();

  public function requestFiltersClasses();

  public function config() : IHostConfig;

  public function appSettings() : AppSettingsInterface;

  public function registerPlugin(IPlugin $plugin);

  public function createServiceRunner(ActionContext $context);

  /**
   * The absolute url for this request
   */
  public function resolveAbsoluteUrl();

  public function tryResolve($dependency);

  public function serviceController();

  public function addRequestFiltersClasses(IHasRequestFilter $filter) : void;
}
