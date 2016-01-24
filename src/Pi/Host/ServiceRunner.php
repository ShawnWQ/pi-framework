<?hh

namespace Pi\Host;
use Pi\Interfaces\IPiHost;
use Pi\Interfaces\IHasAppHost;
use Pi\Host\ActionContext;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IServiceRunner;
use Pi\Interfaces\IMessage;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;
use Pi\Host\HostProvider;
use Pi\Logging\LogMannager;

/**
 * ServiceRunner execute requests and messages using the registered IMessageService
 * It's used only for internal requests. The HTTP request first received is executed by the AppHost
 * Others requests are executed from the ServiceRunner
 */
class ServiceRunner<TRequest>
  implements IServiceRunner<TRequest>{
  protected $requestFilters;


  protected $responseFilters;
  protected $messageFactory;
  protected $logger;

    public function __construct(protected IPiHost &$appHost, protected ActionContext &$context){
    $this->context = $context;
    $this->logger = LogMannager::getLogger(get_class($this));
    $this->messageFactory = $appHost->tryResolve('IMessageFactory');
  // request/response filters are get from context

  }

  public function onBeforeExecute(IRequest $requestContext, TRequest $request)
  {

  }

  public function onAfterExecute(IRequest $requestContext, $response)
  {

  }

  public function handleException(IRequest $request, TRequest $requestDto, $ex)
  {

  }

  public function executeMessage(IRequest $requestContext, $instance, IMessage $request)
  {

  }

  public function publish($type, $messageBody)
  {
    $producer = $this->messageFactory->createMessageProducer();
    $response = $producer->publish($type, $messageBody);
    return $response;
  }
  public function publishMessage($type, IMessage $message)
  {

  }

  public function executeOneWay(IRequest $requestContext, $instance, TRequest $request){

      $msgFactory = $this->appHost->tryResolve('IMessageFactory');
      if(is_null($msgFactory))
      {
        return $this->execute($requestContext, $instance, $request);
      }
      $producer = $msgFactory->createMessageProducer();
      $response = $producer->publish(get_class($request), $request);

      return $response;
      //return WebRequestUtils.GetErrorResponseDtoType(request).CreateInstance();
  }

  /*
   * The default execute. Called if no message producer is registered
   */
  public function execute(IRequest $context, $instance, TRequest $requestDto)
  {

    try {
      $httpResponse = $this->appHost->tryResolve('IResponse');
      
      /*$this->appHost->callGlobalRequestFilters($context, $httpResponse);
      
  */
      $this->appHost->callRequestFiltersClasses($context, $httpResponse, $requestDto);

      $this->appHost->callPreInitRequestFiltersClasses($context, $httpResponse, $requestDto);
      $this->appHost->callPreRequestFilters($context, $httpResponse);
      $this->appHost->callRequestFiltersClasses($context, $httpResponse, $requestDto);
      $this->onBeforeExecute($context, $requestDto);
      $context->setDto($requestDto);
      $a  =  $instance();
      $response = $a($context);
      //$response = call_user_func($instance, $context->dto());
      

      $context->setResponse($response);

      $res = $this->afterEachRequest($context, $requestDto, $response);

      
      $this->appHost->callPostRequestFilters($context, $httpResponse);

      $this->appHost->callActionResponseFilters($context, $httpResponse);

      $this->appHost->callResponseFilters(0, $context, $httpResponse);

      $this->appHost->callGlobalResponseFilters($context, $httpResponse);

      $this->appHost->callResponseFilters(-1, $context, $httpResponse);

      $this->appHost->callOnEndRequest($context, $httpResponse);
      return $res;
    }
    catch(\Exception $ex)
    {
      throw $ex;
      $this->logger->error('Error ocurred executing the ServiceRunner.execute: ' . $ex->getMessage(), $ex);
      return null;
    }
  }

  public function afterEachRequest(IRequest $context, TRequest $requestDto, $response){
    $this->logger->debug(
      sprintf('Executing afterEarchRequest for request %s', get_class($requestDto))
    );
//    $this->appHost->callGlobalResponseFilters();
    $this->onAfterExecute($context, $response);
    return $response;
  }

  public function resolveService($serviceType, IRequest $requestContext)
  {
    $service = $this->appHost->tryResolve($serviceType);
    $requiresContext = in_array('Pi\Interfaces\IRequiresRequest', class_implements($service));
    if($requiresContext)
    {
      $service->setRequest($requestContext);
    }
    return $service;
  }
}
