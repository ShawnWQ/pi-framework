<?hh

namespace Pi\Interfaces;

interface IRequiresRequest {

  public function request() : IRequest;
}
