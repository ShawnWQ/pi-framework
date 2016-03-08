<?hh

namespace Pi\Interfaces;
/**
 * Interface to provide dependency resolver
 */
interface IResolver {
  public function tryResolve(string $alias) : ?IContainable;
}
