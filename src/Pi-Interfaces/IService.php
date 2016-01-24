<?hh

namespace Pi\Interfaces;
use Pi\Interfaces\IResolver;

interface IService
  extends IHasFactory {

public function request() : IRequest;

  public function appHost() : IPiHost;

  public function setAppHost(IPiHost $host);

  public function meta();

  public function setResolver(IResolver $resolver);

  public function resolver(IResolver $resolver) : IService;

  public function globalResolver();

  public function tryResolve(string $name);

  public function createInstance();

  public function getSession();

  public function removeSession() : void;

}
