<?hh

namespace Pi\Interfaces;




/**
 * The IOC Container used by Pi Framework
 */
interface IContainer extends IResolver {

  /**
   * Get a dependency from container
   * Dependencies may or no already be initialized
   * @param  string $type the dependency class name as its registered
   * @return ?\object the dependency or null
   */
  public function get(string $type) : mixed;

  /**
   * Register as a implementation
   * All dependencies of object are automatically auto-wired
   * Resolve dependencies from the constructor
   * @param  string $type The class type
   * @return string $asType The type as is registered
   */
  public function registerAutoWiredAs(string $type, string $asType) : void;

  /**
   * All dependencies of object are automatically auto-wired
   * Resolve dependencies from the constructor
   * @param  string $type the class type
   */
  public function registerAutoWired(string $type);

  /**
   * Register an callable factory function that creates the custom instance
   * The properties and the constructor of the registered type aren't auto-wired
   * @param string $type the class type being registered
   * @param function $closure the factory callback that creates the instance
   */
  public function register(string $type, (function (IContainer): void) $closure) : void;

  /**
   * Register custom instance with a custom type
   * The properties and the constructor of the registered type aren't auto-wired
   * @param string $typeAs the class name type as registered
   * @param mixed $class the instance
   */
  public function registerInstance(mixed $class) : void;

  /**
   * Register custom instance with a custom type
   * The properties and the constructor of the registered type aren't auto-wired
   * @param string $typeAs the class name type as registered
   * @param \object $class the instance
   */
  public function registerInstanceAs(string $typeAs, mixed $class) : void;

  public function registerRepository(string $entityClass, string $repositoryClass, $namespace = null) : void;

  /**
   * Indicates if a class type is registered in container
   * @param  string  $className the class type
   * @return boolean            true if exists
   */
  public function hasRegistered(string $className) : bool;

  /**
   * Indicates if a class type was initialized (and registerd)
   * @param  string  $className the class type
   * @return boolean            true if initialized
   */
  public function hasInstance(string $className) : bool;

}
