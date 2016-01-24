<?hh

namespace Pi\Interfaces;

interface IHasPreInitFilter {
	public function priority() : int;

  	public function requestFilter(IRequest $req, IResponse $res, $responseDto) : void;

  	public function copy() : IHasRequestFilter;
}