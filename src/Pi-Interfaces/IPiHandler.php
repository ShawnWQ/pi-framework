<?hh

namespace Pi\Interfaces;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;

interface IPiHandler {
  /**
   * Return a async task
   * @param $operationName string
   */
  public function processRequestAsync(IRequest $httpReq, IResponse $httpRes, $operationName);

  /**
   * @param $operationName string
   */
  public function processRequest(IRequest $httpReq, IResponse $httpRes, $operationName);
}
