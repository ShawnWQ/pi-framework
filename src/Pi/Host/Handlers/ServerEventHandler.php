<?hh

namespace Pi\Host\Handlers;

class ServerEventHandler extends AbstractPiHandler {

	public function createRequest(IRequest $request, string $operationName)
	{
		
	}

	public function createResponse(IRequest $request, $requestDto)
	{

	}

	public function getResponse(IRequest $httpReq, $request)
	{
		
	}

	public async function processRequestAsync(IRequest $httpReq, IResponse $httpRes, string $operationName) : Awaitable<void>
	{
		try {

		} catch(\Exception $ex) {

		}
	}

	protected function handleException(IRequest $httpReq, IResponse $httpRes, string $operationName, $ex)
	{
		$httpRes->endRequest();
	}
	
	public function processRequest(IRequest $httpReq, IResponse $httpRes, string $operationName)
	{

		$request = $this->createRequest($httpReq, $operationName);	
		$httpReq->setDto($request);
		$response = $this->getResponse($httpReq, $request);
		$callback = function($response) use($httpReq, $httpRes, $request){
			
		};

		$errorCallback = function() {

		};

		return $this->handleResponse($response, $callback, $errorCallback);
	}
}