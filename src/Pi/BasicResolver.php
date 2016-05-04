<?hh
namespace Pi;

use Pi\Interfaces\IResolver;
use Pi\Interfaces\IContainable;

/**
 * The basic resolver
 * To implement a new container extends this or create a resolver implementing IResolver
 */
class BasicResolver implements IResolver{
  public function __construct($container)
  {
    $this->container = $container;
  }

  public function tryResolve(string $alias) : ?object
  {
    return $this->container->tryResolve($alias);
  }

  protected $container;
}
