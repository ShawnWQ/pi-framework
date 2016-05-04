<?hh

namespace Pi\Filters;

interface IHasRequestFilter {

  public function getPriority();
  public function requestFilter($req, $res, $objectDto);
}
