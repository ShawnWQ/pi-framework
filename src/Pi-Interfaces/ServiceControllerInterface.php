<?hh

namespace Pi\Interfaces;

use Pi\Host\OperationHydratorFactory,
	Pi\Host\OperationMetaFactory,
	Pi\Host\OperationDriver,
	Pi\Interfaces\IService,
	Pi\Interfaces\IMessage,
	Pi\Interfaces\IRequest;




/**
 * Service Controller is the Service Registry of Pi Framework
 * At the first time run, the cache isn't registered so every Services are
 * registered, as well routes
 * When the cache is registered, tries to load once from it. If it fails to
 * resolve the service for a specific request, the ServiceController builds
 * again. Only if fails again an error is throwed
 * The routes are always loaded from cache each Request.
 */
interface ServiceControllerInterface {

	/**
	 * Injects the IRequest in Service
	 * @param  IService   $service        Service instance
	 * @param  IRequest $requestContext IRequest
	 */
	static function injectRequestContext(IService $service, IRequest $requestContext) : void;

	public function getOperationHydratorFactory() : OperationHydratorFactory;

	public function getOperationMetaFactory() : OperationMetaFactory;

	public function getOperationDriver() : OperationDriver;

	public function getMessageFactory() : IMessageFactory;

	public function getLog() : ILog;

	public function getCacheProvider() : ICacheProvider;

	/**
	 * Checks if the Cache Provider contains cached information required by Service Controller
	 * like Routes and Services class names.
	 * @return boolean True if a cache is already registered
	 */
	public function isCached() : bool;

	/**
	 * Checks if the Cache Provider contains cache information updated agains the current Pi Host
	 * required by Service Controller like Routes and Services class names
	 * The version is resolved from PiHost constant VERSION, ie: PiHost::VERSION
	 * @return boolean [description]
	 */
	public function isCachedNewer() : bool;
	
	/**
	 * Register the given Service
	 * Components like Services, Plugins and Filters are registered per request
	 * A new instance of a Service is only initialized when it's required for a
	 * execution, otherwise itsnt loaded
	 * @param  string $className [description]
	 * @return [type]            [description]
	 */
	public function registerService(string $className) : void;

	/**
	 * Returns true if a give Service is already registered
	 * @param  string  $className The service class name
	 * @return boolean            True if the service is registered
	 */
	public function hasServiceRegistered(string $className) : bool;

	/**
	 * A Service Executor is a closure responsable for preparing the Service instance 
	 * to execute a request. The instance is already resolved and passed to it (from IOC)
	 * A Service Executor is registered when a request is going to use it
	 * @param  string      $requestType The Request DTO class name
	 * @param  string      $serviceType The Service class name
	 * @param  string      $method      The function method of the current $requestType
	 * @param  IHasFactory $factory     The service instance
	 */
	public function registerServiceExecutor(string $requestType, string $serviceType, string $method, IHasFactory $factory) : void;

	/**
	 * Returns true if a give Service Executor was already registered
	 * @param  string  $className The service class name
	 * @return boolean            True if the service executor is registered
	 */
	public function hasServiceExecutor(string $className) : bool;

	public function execute($dto, ?IRequest $httpReq = null) : mixed;

	public async function executeAsync($dto, ?IRequest $httpReq = null) : Awaitable<mixed>;

	public function executeMessage(IMessage $message, ?IRequest $httpReq = null) : mixed;

	public function executeMessageAsync(IMessage $message, ?IRequest $httpReq = null) : Awaitable<mixed>;

	
}