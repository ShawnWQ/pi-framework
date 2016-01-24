<?hh

namespace Pi\Interfaces;

interface ISessionFactory {
  
  public function getOrCreateSession(IRequest $req, IResponse $res);
}
