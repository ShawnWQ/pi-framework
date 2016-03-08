<?hh

namespace Pi\Interfaces;

interface IRequestHasSort {
	
	public function getSortOrder() : int;

	public function getSortBy() : int;
}