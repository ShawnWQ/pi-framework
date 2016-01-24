<?hh

namespace Pi\Filters;

interface IHasPreRequestFilter {

    public function preRequestFilter($req, $res);
}
