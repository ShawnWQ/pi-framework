<?hh

namespace Pi\Interfaces;

interface IRequestHasLimit {
	
	public function getSkip() : int;

	public function getLimit() : int;
}