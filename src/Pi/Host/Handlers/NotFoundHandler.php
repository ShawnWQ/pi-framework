<?hh

namespace Pi\Host\Handlers;

use Pi\HttpStatusCode,
    Pi\Interfaces\IRequest,
    Pi\Interfaces\IResponse,
    Pi\ServiceModel\NotFoundRequest;

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
    $httpRes->setStatusCode(HttpStatusCode::NotFound);
    
    
    $callback = function($response) use($httpReq, $httpRes){
      $httpRes->writeDto($httpReq, $response);
      //$httpRes->endRequest();
    };
    $errorCallback = function() {

    };
    return $this->handleResponse($response, $callback, $errorCallback);
  }
}
