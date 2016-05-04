<?hh

namespace Pi;

use Pi\Interfaces\IContainer;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IResolver;
use Pi\Interfaces\IService;
use Pi\Validation\AbstractValidator;
use Pi\Host\HostProvider;

/**
 * The container implementation
 * Container register dependencies using the IContainable interface
 * Implementation for IContainable is adopted to keep a way to know which objects really are manage by the IOC.
 * I believe this is the fast way rather than use reflection
 * IContainable implementations requires the execution of **ioc** method to inject the IResolver instance and inject the dependencies on itself
 * ATM the code is confuse but my goal is to always implement the interface
 * I'll remove the $registeredNonContainable later as non containable are almost injected by reflection and call also implement the IContainable
 */
class PiContainer implements IContainer, IResolver{

  /**
   * Singleton instances registered
   */
  private Map<string, IContainable> $instances = Map {};

  /**
   * Registered dependencies
   */
  private Map<string, (function (IContainer): IContainable)> $registered = Map {};

  /**
   * Registered non containable
   */
  private Map<string, (function() : null)> $registeredNonContainable = Map {};

  /**
   * Validators registered by the Application and Plugins
   */
  private Map<string, AbstractValidator> $validatorsRegistered = Map{};

  protected Map<string,string> $aliases = Map{};

  protected $reflClass = array();

  private Map<string,string> $repositoriesAlias = Map {};

  public function isRegistered(string $key) : bool
  {
    return $this->registered->contains($key);
  }
  
  public function getService($serviceInstance)
  {
    return $this->get(get_class($serviceInstance));
  }

  public function getValidator($instance)
  {
    $validator = $this->validatorsRegistered->get(get_class($instance));
    if($validator === null) {
      return null;
    }
    $validator->setAppHost(HostProvider::instance());
    return $validator;
  }

  public function registerValidator($instance, AbstractValidator $validator)
  {
    if(!is_string($instance)) {
      $instance = get_class($instance);
    }
    $this->validatorsRegistered[$instance] = $validator;
  }

  public function tryResolve(string $alias) : ?IContainable
   {
     return $this->get($alias);
   }

  public function tryGet(string $alias)
  {
    try {
      return $this->get($alias);
    } catch(\Exception $ex) {
      return null;
    }
  }

  public function remove(string $alias) : void
  {
    if($this->registered->contains($alias)) {
      $this->registered->remove($alias);
    }

    if($this->aliases->contains($alias)) {
      $this->aliases->remove($alias);
    }

    if($this->instances->contains($alias)) {
      $this->instances->remove($alias);
    }
  }

  /**
  * Returns the instance associated with the supplied alias.
  */
  public function get(string $alias): ?IContainable
  {

    if(!$this->registered->contains($alias)) {

      if($this->aliases->contains($alias)) {
        $alias = $this->aliases->get($alias);
      } else {
        return null; // it must be registered
      }
    }

    $instance = $this->instances->get($alias);

    if($instance !== null) { //return this instance if already exists

      return $instance;
    }

    // Not created yet
     $closure = $this->registered->get($alias);

     if ($closure !== null) {
        $fn = $closure($this);
         $this->instances->set($alias, $fn);

     } else {

       $closure = $this->registeredNonContainable->get($alias);

       if($closure !== null){
         $this->instances->set($alias, $closure());
       }
     }

     $instance = $this->instances->get($alias);
     if($instance instanceof IService) { // autowire services dependencies

        $this->autoWireService($instance);
        $instance->setResolver($this);
      }

    if(!isset($this->reflClass[get_class($instance)])){
      $this->reflClass[get_class($instance)] = new \ReflectionClass(get_class($instance));
    }

     if($instance instanceof IService) {
       return $this->autoWireService($instance);
     }

     $this->autoWirePublicProp($instance);

     return $instance;
   }

   /**
    * Returns a new instance already registered in Container. If not, return null
    */
   public function getNew(string $alias): ?IContainable
   {
       if (!$this->registered->contains($alias)) {
           return null; // return null if it'isnt registered
       }

       // execute the closure and return its return
       $closure = $this->registered->get($alias);
       return ($closure !== null) ? $closure($this) : null;
   }

  /**
   * Auto wire service dependencies from
   * - public properties of the class (Services are only access by core code, no need for programmer to declare others public properties than dependencies)
   * - constructor parameters - types are resolved without being the real implementations thanks to hacklang. For those case make them null by default
   */
  public function autoWireService($serviceInstance)
  {


    if(!$serviceInstance instanceof IService) {
      //throw new \Exception(sprintf('Not a valid service, instead: %s',  get_class($serviceInstance)));
    } else {
      $serviceInstance->setAppHost(HostProvider::instance());
    }
    $oid = get_class($serviceInstance);


    // Register the service if not
    if(!isset($this->reflClass[$oid])){
      $this->reflClass[$oid] = new \ReflectionClass(get_class($serviceInstance));
    }

    $publics = $this->reflClass[$oid]->getProperties(\ReflectionProperty::IS_PUBLIC);
    if(is_array($publics) && count($publics) > 0) {
      return $this->autoWirePublicProp($serviceInstance);
    }

    $constructor = $this->reflClass[$oid]->getConstructor();
    if($constructor !== null && is_array($constructor->getParameters()) && count($constructor->getParameters()) > 0) {
      return $this->autoWireByParams($serviceInstance, $constructor->getParameters());
    }


    return $this->autoWire($serviceInstance);
  }

  protected function autoWirePublicProp($instance)
  {

    $props = $this->reflClass[get_class($instance)]->getProperties(\ReflectionProperty::IS_PUBLIC);
    foreach($props as $property) {
      $type = $property->getTypeText();
      if(!is_string($type)) continue;

      if(get_parent_class($type) === 'Pi\Odm\MongoRepository') {
        $repoInstance = $this->getRepository($type);
        $property->setValue($instance, $repoInstance);
      }
      else {
        $dep = $this->tryGet($type);
        if($dep === null) continue;
        $property->setValue($instance, $dep);
      }

    }

    return $instance;
  }

  protected $namespaces = array();
  public function getRepositoryByNamespace(string $namespace)
  {
    if(!isset($this->namespaces[$namespace])) {

      return;
    }

    return $this->getRepository($this->namespaces[$namespace]);
  }

  public function getRepository($entityInstance)
  {
    if(!is_string($entityInstance)){
      $entityInstance = get_class($entityInstance);
    }

    //$instance = $this->repositoriesAlias->get($entityInstance);
    $instance = $this->get($entityInstance);

    $this->autoWireService($instance);


    return $instance;
  }

  public function registerRepository($entityInstance, $repositoryInstance, $namespace = null) : void
  {
    if(!is_string($entityInstance)) {
      $entityInstance = get_class($entityInstance);
    }
   
    if(!is_null($namespace)) {
      
      $this->namespaces[$namespace] = $repositoryInstance;
    }
   
    $name = get_class($repositoryInstance);

    if(is_string($repositoryInstance)) {
      $repositoryInstance = $this->autowire($repositoryInstance);
    }

      $fn = function(IContainer $ioc) use($repositoryInstance, $entityInstance) {

        $dm = $ioc->get('MongoManager');

        if($dm === null) {
          throw new \Exception('The MongoManager dependency hasnt been registered yet.');
        }

        $class = $dm->getClassMetadata($entityInstance);

        $repositoryInstance->setClassMetadata($class);

        $repositoryInstance->ioc($this);

        return $repositoryInstance;
      };

      //$this->repositoriesAlias->add(Pair {get_class($entityInstance), $name});
      $this->repositoriesAlias->add(Pair {$name, $entityInstance});
      $this->registered->add(Pair { $name, $fn});
  }

  public function createInstance(string $className)
  {
    $this->reflClass[$className] = new \ReflectionClass($className);

    $constructor = $this->reflClass[$className]->getConstructor();

    $serviceInstance = $this->reflClass[$className]->newInstanceWithoutConstructor();

    return $serviceInstance;
  }

  protected function autoWireByParams($serviceInstance, $params)
  {
    foreach($params as $param) {

      $name = $param->info['name'];
      $t = isset($param->info['type_hint']) ? $param->info['type_hint'] :
        (isset($param->info['type']) ? $param->info['type'] : null);
      if($t === null) continue;

      $t = ltrim($t, '?'); // optional dependencies

      $dp = $this->get($t);
      if($dp === null) {

        continue;
      }
      $serviceInstance->$name = $dp;
    }
    return $serviceInstance;
  }

  public function autoWire($instance)
  {

    $reflClass = is_string($instance)
    ? new \ReflectionClass($instance)
    : new \ReflectionClass(get_class($instance));

    foreach($reflClass->getMethods() as $method) {

      if($method->getAttribute('Inject') !== null){
        $name = $method->name;
        $d = $method->getAttribute('Inject');
        if(is_array($d) && count($d) >= 1){
          $dependency = $this->get($d[0]);
          $instance->$name($dependency);
        } else {
          $params = $method->getParameters();
          if(is_array($params) && count($params) === 1) {
            $class = $params[0]->info['type'];
            $dependency = $this->get($class);
            if($dependency !== null) {
              $instance->$name($dependency);
            }
          }
        }

      }
    }
  }

  /**
    * Registers an alias and closure mapping.
    */
   public function register(string $alias, (function (IContainer): IContainable) $closure): void
   {
       $this->registered->add(Pair {$alias, $closure});
   }

   public function registerNonContainable(string $alias, (function (): null) $closure): void
   {
     $this->registeredNonContainable->add(Pair {$alias, $closure});
   }

   public function registerInstance($instance, $alias = null)
   {
     if($alias === null) {
       $alias = get_class($instance);
     }

     $fn = function(IContainer $container) use($instance){
       return $instance;
     };

     $this->registered->add(Pair{$alias, $fn});
   }

   public function registerAlias($alias, $original)
   {
     $this->aliases->add(Pair {$alias, $original});
   }
}
