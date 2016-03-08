<?hh

namespace Pi\Interfaces;

/**
 * Interface used to inject current application host in the object
 * Injection is done calling the method setAppHost, not by attributes or public property (yet, then just remote setAppHost from interface)
 */
interface IHasAppHost {

  //public function appHost();

  public function setAppHost(IPiHost $appHost);
}
