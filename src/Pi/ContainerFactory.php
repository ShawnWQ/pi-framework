<?hh

namespace Pi;
use Pi\Interfaces\IContainer;
use Pi\Container;

/**
 * Container Factory
 * Create a new Container
 */
class ContainerFactory {

  public function createContainer() : IContainer{
    return new Container();
  }
}
