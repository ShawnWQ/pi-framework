<?hh

namespace Pi;

use Pi\Interfaces\IMessageFactory;
use Pi\Interfaces\IMessageProducer;
use Pi\Interfaces\ICachedClient;
use Pi\Interfaces\IServiceBase;
use Pi\Interfaces\IService;
use Pi\Interfaces\IResolver;
use Pi\Interfaces\IPiHost;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\IContainable;
use Pi\Host\BasicResponse;
use Pi\Host\BasicRequest;
use Pi\Host\HostProvider;
use Pi\Interfaces\IHasFactory;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IRequiresRequest;
use Interfaces\IHasAppHost;
use Host\ServiceMeta;
use Pi\EventSubscriber;
use Pi\Auth\Interfaces\IAuthSession;

/**
 * @description Generic IService base class
 */
abstract class Service extends EventSubscriber  implements
	IService,
   	\Pi\Interfaces\IServiceBase,
   	\Pi\Interfaces\IHasAppHost,
   	IHasFactory,
   	IContainable,
   	IRequiresRequest {

	/**
	* @var \Pi\Host\ServiceMeta;
	*/
	protected $meta;
	protected $serviceType;

	protected $cachedClient; //ICachedClient

	protected $request;

	protected $requestContext;
	/**
	* @var IMessageFactory
	*/
	protected $messageFactory;
	/**
	* @var IMessageProducer
	*/
	protected $messageProducer;

	protected $cache; // ICacheClient RedisManager

	protected $db;

	protected $redisClient;

	protected $sessionFeature;

	/**
	 * @param IResolver
	 */
	protected $resolver;

	protected $responseUtils;

	public EventManager $eventManager;

	protected $session;

	public function __construct()
	{
		$this->serviceType = get_class($this);
		$this->meta = new \Pi\Host\ServiceMeta($this->serviceType);
	/*	$rc = new \ReflectionClass(get_class($this));

		$methods = $rc->getMethods();

		foreach($methods as $method)
		{
		 $name = $method->name;
		  $attrs = $rc->getMethod($name)->getAttributes();

		  if(!array_key_exists('ServiceRequest', $attrs)) return;

		  $requestType = $rc->getMethod($name)->getAttribute('ServiceRequest');
		  if(!is_null($requestType)) {
		    $this->meta->add($requestType[0], $name);
		  }
		}*/
	}

    public function iocType() : string {
		return get_called_class();
	}

	public function ioc(IContainer $container)
	{
		$this->eventManager = $container->get('EventManager');

		$this->resolver = $container;
	}

	public function eventManager()
	{
		return $this->appHost->eventManager();
	}


	public function createInstance()
	{
	 $ref = new \ReflectionClass(get_called_class());
	 return new $ref->name();
	}

	public function appHost()
	{
	 return $this->appHost;
	}

	public function setAppHost(IPiHost $host) {
	 $this->appHost = $host;
	}
	protected $appHost;


	public function meta()
	{
	 return $this->meta;
	}


    public function resolver(IResolver $resolver) : IService
    {
      return $this;
    }
    public function setResolver(IResolver $resolver){
		$this->resolver = $resolver;
		return $this;
	}
	public function getResolver() : IResolver {
		return is_null($this->resolver) ? $this->globalResolver() : $this->resolver;
	}
	public function globalResolver(){
		// injected
	}

	/**
	 * @descriptieon
	 * Resolve an dependency
	 * @return The dependency
	 */
	public function tryResolve(string $name) {
		return $this->getResolver()->tryResolve($name);
	}

	public function responseUtils()
	{
		return $this->responseUtils;
	}

  /**
   * Resolve dependency
   * @param  string $name [description]
   * @return [type]       [description]
   * @throws \Exception not found dependency
   */
  public function resolve(string $name)
  {
    return $this->tryResolve($name);
  }

  public function execute($requestDto)
  {
    return HostProvider::instance()->serviceController()->execute($requestDto, $this->request);
  }

	/**
	 * @description
	 * Register an IService
	 */
	public function resolveService<T>()
	{
		//$service = $this->tryResolve<T>();
		//$requiresContext = $service as IRequiresContext;
		//if(!is_null($requiresContext)) {
		//	$req = $this->getRequest();
			//$requiresContext->setRequest($req);
	//	}
	}

	/**
	 * @name Publish Message
	 * @description
	 * Publish a IMessage using the T IRequest as message body
	 * Providers are available like Redis Pub/Sub and MQRabbit
	 * Services use message pub/sub to comunicate with each other
	 */
	public function publishMessage<T>(T $message){
		if(is_null($this->messageProducer)) {
			throw new \Exception("No IMessageFactory was registered in container, message cannot be published");
		}
		$this->messageProducer->publish($message);
	}

	/**
	 * @description
	 * Get or create and get the IMessageFactory
	 */
	private function getMessageFactory()
	{
			if(is_null($this->messageFactory)) {
				$this->messageFactory = $this->appHost->tryResolve('IMessageFactory');
			}
			return $this->messageFactory;
	}

	/**
	 * @descripion
	 * Get or create and get the IMessageProducer
	 */
	private function getMessageProducer(){
		if(is_null($this->messageProducer)) {
			$this->messageProducer = $this->getMessageFactory()->createMessageProducer();
		}
		return $this->messageProducer;
	}

	public function request() : IRequest{
		return $this->request;
	}

	public function response()
	{
		return $this->resolve('IResponse');
		//return is_null($this->request) ? null : $this->request->response();
	}



	/**
	 * @description
	 * Get the IRequest for the current context
	 *
	 */
	public function getRequest(){
    	return $this->request;
	}

	public function setRequest(IRequest $request)
	{
		$this->request = $request;
	}

	/**
	 * @description
	 * Get the IResponse for the current context
	 */
	public function getResponse(){
		return !is_null($this->getRequest()) ? $this->getRequest()->getResponse() : null;
	}

	/**
	 * @return ICachedClient
	 */
	public function cache()
	{
			if(is_null($this->cachedClient)){
				$this->cachedClient = $this->tryResolve('ICacheProvider');
				if(is_null($this->cachedClient)) {
					//$this->cachedClient = random
				}
			}

			return $this->cachedClient;
	}

	public function dbFactory()
	{
		return $this->tryResolve('IDbConnectionFactory');
	}

	public function dbConnection()
	{
		if(is_null($this->db)) {
			 $this->db = $this->dbFactory()->openDbConnection();
		}

		return $this->db;
	}

	public function redisManager()
	{
		return $this->tryResolve('IRedisClientsManager');
	}

	public function redisClient(){
		if(is_null($this->redisClient)) {
			$this->redisClient = $this->redisManager();
		}

		return $this->redisClient;
	}

	public function messageFactory()
	{
		if(is_null($this->messageFactory)) {
			//$this->messageFactory = $this->tryResolve<IMessageFactory>();
		}

		return $this->messageFactory;
	}

	public function setMessageFactory(IMessageFactory $factory) : void
	{
		$this->messageFactory = $factory;
	}

	public function messageProducer()
	{
		if(is_null($this->messageProducer)) {
			$this->messageProducer = $this->messageFactory()->createMessageProducer();
		}
		return $this->messageProducer;
	}

	public function authSession()
	{
		//$auth = $this->tryResolve<IUserSession>();
		if(1 == 1) // DAH! ver se a auth foi gerada ja ou nao ..?!?!!..
		{
			$auth = $this->sessionFeature->getOrCreateSession($this->cache(), $this->request(), $this->response());
		}
	}

	public function getSession($reload = false) : IAuthSession
	{
			$req = $this->request;
			if(is_null($req)) {
				return null;
			}
			return $req->getSession($reload);
	}

  public function appConfig()
  {
    return $this->appHost->config();
  }

	public function isAuthenticated()
	{
		return $this->getSession()->isAuthenticated();
	}

	public function dispose()
	{
		if($this->redisClient !== null)
			$this->redisClient->dispose();
		if($this->messageProducer !== null) {
			$this->messageProducer->dispose();
		}
		// RequestContext.Instance.ReleaseDisposables();
	}

	public function removeSession() : void
	{

	}

	public function getEventsSubscribed()
	{
		return array();
	}
}
