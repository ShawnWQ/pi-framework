<?hh

namespace Pi\Interfaces;

interface IContainer extends IResolver {

  public function register(string $alias, (function (IContainer): IContainable) $closure): void;

  public function autoWire($instance);

  public function get(string $alias): ?IContainable;

  public function getNew(string $alias): ?IContainable;

  public function registerRepository($entityInstance, $repositoryInstance) : void;

  public function createInstance(string $className);

  public function autoWireService($serviceInstance);
}
