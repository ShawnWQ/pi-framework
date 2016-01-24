<?hh

namespace Pi\Host\Handlers;

use Pi\Interfaces\Host\Handlers\IHttpAsyncHandler;
use Pi\Interfaces\Host\Handlers\IPiHandler;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;

class HttpAsyncHandler implements IPiHandler {

	//asyncxx
	public async function processRequestAsync(IRequest $httpReq, IResponse $httpRes, string $operationName): Awaitable<void>
	{
		throw new \Exception('not implemented');
	}
    
    public function processRequest(IRequest $httpReq, IResponse $httpRes, string $operationName) : void
    {

    }

    public function beginProcessRequest(IHttpRequest $context, $asyncCallback, $dto)
    {

    }

	/**
	 * Provides an asynchronous process End method when the process ends.
	 */
	public function endProcessRequest($asyncResult)
	{

	}
}