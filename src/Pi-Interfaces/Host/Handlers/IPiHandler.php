<?hh

namespace Pi\Interfaces\Host\Handlers;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;

interface IPiHandler {
	public function processRequestAsync(IRequest $httpReq, IResponse $httpRes, string $operationName) : async;
    public function processRequest(IRequest $httpReq, IResponse $httpRes, string $operationName) : void;
}