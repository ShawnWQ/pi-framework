<?hh
namespace Pi\Host\Handlers;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\Interfaces\Host\Handlers\IPiHandler;
use Pi\Interfaces\Host\Handlers\IHttpAsyncHandler;
use Pi\Host\HostProvider;
use Pi\Host\Handlers\HandlerAttribute;
 
abstract class AbstractPiHandler extends HttpAsyncHandler {

	protected $appHost;

	public function __construct(protected ?Set<HandlerAttribute> $handlerAttributes = null)
	{
		$this->appHost = HostProvider::instance();
	}

	public abstract function createRequest(IRequest $request, string $operationName);

	public abstract function createResponse(IRequest $request, $requestDto);

	public function getCustomRequestFromBinder(IRequest $httpReq, $requestType)
	{
		/*
		Func<IRequest, object> requestFactoryFn;
            HostContext.ServiceController.RequestTypeFactoryMap.TryGetValue(
                requestType, out requestFactoryFn);

            return requestFactoryFn != null ? requestFactoryFn(httpReq) : null;
            */
	}

	public function getOperationType(string $operationName)
	{
		return $this->appHost->serviceController()->getRequestTypeByOperation($operationName);
	}

	public function handleResponse($response, $callback, $errorCallback)
	{
		$callback($response);
	}

	public static function deserializeHttpRequest($operationType, IRequest $httpRequest, string $contentType)
	{
		$method = $httpRequest->verbe();
	}


	public static function executeService($request, IRequest $httpRequest)
	{
		return HostProvider::execute($request, $httpRequest);
	}

}
