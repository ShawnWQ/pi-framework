<?hh

namespace Pi\Interfaces;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\Interfaces\IHasRequestFilter;


interface IHasRequestFilter {
  public function priority() : int;

  public function requestFilter(IRequest $req, IResponse $res, $responseDto) : void;

  public function copy() : IHasRequestFilter;
}
