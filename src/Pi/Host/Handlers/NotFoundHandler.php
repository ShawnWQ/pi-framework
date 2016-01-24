<?hh

namespace Pi\Host\Handlers;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\ServiceModel\NotFoundRequest;

class NotFoundHandler extends RestHandler {

  public function processRequestAsync(IRequest $httpReq, IResponse $httpRes, string $operationName)
  {
    return $this->processRequest($httpReq, $httpRes, $operationName);
  }
  
  public function processRequest(IRequest $httpReq, IResponse $httpRes, string $operationName)
  {
    $request = new NotFoundRequest();

    $httpReq->setDto($request);
    $response = $this->getResponse($httpReq, $request);
    
    $callback = function($response) use($httpReq, $httpRes){
      $httpRes->writeDto($httpReq, $response);
      //$httpRes->endRequest();
    };
    $errorCallback = function() {

    };
    return $this->handleResponse($response, $callback, $errorCallback);
  }
}
