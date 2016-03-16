<?hh

namespace Pi;

use Pi\ReuseScope,
    Pi\Interfaces\IContainer,
    Pi\Interfaces\IContainable,
    Pi\Interfaces\IResolver,
    Pi\Interfaces\IService,
    Pi\Interfaces\ICacheProvider,
    Pi\Interfaces\HydratorFactoryInterface,
    Pi\Interfaces\DtoMetadataInterface,
    Pi\Validation\AbstractValidator,
    Pi\Host\HostProvider,
    Pi\Common\Mapping\HydratorPoviderBase;




/**
 * The container implementation
 * Dependencies auto wired or registered only as typed will only be initialized if needed
 */
class StaticContainer implements IContainer { 

  const CACHE_PREFIX = 'ioc::';

  const NAME = 'Pi\StaticContainer';

  /**
   * Singleton instances registered
   */
  protected array $instances;

  protected array $registered;

  protected array $aliases;

  protected array $aliasesRepo;  

  use HydratorPoviderBase {
    HydratorPoviderBase::__construct as __hydratorConstruct;
  }

  public function __construct(
    ICacheProvider $cacheProvider
  )
  {
    $this->reset();
    $this->__hydratorConstruct('/tmp', 'IOC');
  }

  public function reset() : void
  {
    $this->registered = array();
    $this->aliases = array();
    $this->aliasesRepo = array();
    $this->instances = array();
  }

  /**
   * Register as a implementation
   * All dependencies of object are automatically auto-wired
   * Resolve dependencies from the constructor
   * @param  string $type The class type
   * @return string $asType The type as is registered
   */
  public function registerAutoWiredAs(string $type, string $asType) : void
  {
    $this->registered[$asType] = $type;
    $this->aliases[$type] = $asType;
  }

  /**
   * All dependencies of object are automatically auto-wired
   * Resolve dependencies from the constructor
   * @param  string $type the class type
   */
  public function registerAutoWired(string $type)
  {
    $this->registered[$type] = true;
  }

  /**
   * Register an callable factory function that creates the custom instance
   * The properties and the constructor of the registered type aren't auto-wired
   * @param string $type the class type being registered
   * @param function $closure the factory callback that creates the instance
   */
  public function register(string $type, (function (IContainer): void) $closure) : void
  {
    $this->registered[$type] = $closure;
  }

  /**
   * Register an callable factory function that creates the custom instance
   * The properties and the constructor of the registered type aren't auto-wired
   * @param string $type the class type being registered
   */
  public function registerAlias(string $type, string $alias) : void
  {
    $this->aliases[$alias] = $type;
  }

  /**
   * Register custom instance
   * The properties and the constructor of the registered type aren't auto-wired
   * @param  \object $class [description]
   * @return [type]         [description]
   */
  public function registerInstance(mixed $class) : void
  {
    $type = get_class($class);
    $this->registered[$type]  = true;
    $this->instances[$type] = $class;
  }

  /**
   * Register custom instance with a custom type
   * The properties and the constructor of the registered type aren't auto-wired
   * @param string $typeAs the class name type as registered
   * @param \object $class the instance
   */
  public function registerInstanceAs(string $typeAs, mixed &$class) : void
  {
    $type = get_class($class);
    $this->registered[$typeAs] = $type;
    $this->aliases[$type] = $typeAs;
    $this->instances[$typeAs] = $class;
  }

  public function autoWire($class)
  {
    $className = get_class($class);
    if(!$this->hasRegistered($className)) {
      $this->registerInstance($class);
    }
    return $this->get($className);
  }

  /**
   * Get a dependency from container
   * Dependencies may or no already be initialized
   * @param  string $type the dependency class name as its registered
   * @throws \InvalidArgumentException If the $type isnt registered in Container
   * @return ?\object the dependency or null
   */
  public function get(string $type) : mixed
  {
    if(isset($this->instances[$type])) {
      return $this->instances[$type];
    } else if(isset($this->aliases[$type]) && isset($this->instances[$this->aliases[$type]])) {
      return $this->instances[$this->aliases[$type]];
    }

    if(!isset($this->registered[$type])) {
      if(isset($this->aliases[$type]) && isset($this->registered[$this->aliases[$type]])) {
        $type = $this->aliases[$type];
      } else {
        throw new \Exception("Depedendency $type not registered in IOC");
      }
    }
    
    if(is_callable($this->registered[$type])) {
      return $this->instances[$type] = $this->registered[$type]($this);
    } else if($this->registered[$type] === true) {
      return self::createInstance($this, $type, InjectionScope::Constructor);
    } else if(is_string($this->registered[$type])) {
      return $this->instances[$type] = self::createInstance($this, $this->registered[$type], InjectionScope::Constructor);
    }
  }


  public function registerRepository(string $entityClass, string $repositoryClass, $namespace = null) : void
  {

    $name = $repositoryClass;
    $fn = function(IContainer $ioc) use($repositoryClass, $entityClass) {
      $repositoryInstance = StaticContainer::createInstance($this, $repositoryClass, InjectionScope::Public);
      $dm = $ioc->get('MongoManager');

      if($dm === null) {
        throw new \Exception('The MongoManager dependency isnt registered.');
      }

      $class = $dm->getClassMetadata($entityClass);

      $repositoryInstance->setClassMetadata($class);

      $repositoryInstance->ioc($this);

      return $repositoryInstance;
    };

    if(!isset($this->registered[$name])) { // Not registered yet in IOC
      $this->registered[$name] = $fn;
    }
    $this->aliasesRepo[$name] = $entityClass;
  }

  /**
   * Indicates if a class type is registered in container
   * @param  string  $className the class type
   * @return boolean            true if exists
   */
  public function hasRegistered(string $className) : bool
  {
    return isset($this->registered[$className]) || isset($this->aliases[$className]);
  }

  /**
   * Indicates if a class type was initialized (and registerd)
   * @param  string  $className the class type
   * @return boolean            true if initialized
   */
  public function hasInstance(string $className) : bool
  {
    return isset($this->instances[$className]) || 
      (isset($this->aliases[$className]) && isset($this->instances[$this->aliases[$className]]));
  }


  /**
   * [tryResolve description]
   * @param  string $alias [description]
   * @return [type]        [description]
   */
  public function tryResolve(string $alias) : ?mixed
  {
     try {
      return $this->get($alias);
     }
     catch(\Exception $ex) {
      return null;
     }
  }

  public function remove(string $name) : void
  {
    if(isset($this->registered[$name])) {
      unset($this->registered[$name]);
    }

    if(isset($this->aliases[$name])) {
      unset($this->aliases[$name]);
    }

    if(isset($this->instances[$name])) {
      unset($this->instances[$name]);
    }
  }

  protected function hasCached(string $className) : bool
  {
    return $this->cacheProvider->contains($className) ;
  }


  protected function cache(string $className, Set<string> $dependencies)
  {

  }

  public function hydrate($document, $data)
  {

  }

  public function generateHydratorClass(array $map, string $hydratorClassName, string $fileName)
  {
    $code = '';
    $hydratorNamespace = $this->hydratorNamespace;
    $className = $map['className'];
    
    foreach ($map['dependencies'] as $dependencyClass) {
      $code .= sprintf(<<<EOF
    \$dependencies[] = \$this->container->get('$dependencyClass');
EOF
      );
    }

    $code = sprintf(<<<EOF
<?hh

namespace $hydratorNamespace;

use Pi\Interfaces\IContainer;




/**
 * IOC Hydrator class
 * Generated by Pi Framework
 */
class $hydratorClassName {

  public function __construct(
    protected IContainer &\$container
  )
  {
  
  }

  public function get() : \\$className
  {
    \$dependencies = array();
    %s
    return new \\$className(...\$dependencies);
  }
}
EOF
    ,
    $code);
  

    $tmpFileName = $fileName . '.' . uniqid('', true);
     try {
      
      $r = file_put_contents($tmpFileName, $code); 
     }
     catch(\Exception $ex) {
      
      throw $ex;
     }

    if( copy($tmpFileName, $fileName) ) {
      unlink($tmpFileName);
    }
  }

  protected $metadata = array();

  public function getMetadataFor(string $className) : array
  {
    if(isset($this->metadata[$className])) {
      return $this->metadata[$className];
    }
    $rflClass = new \ReflectionClass($className);
    $map = array(
      'rflClass' => $rflClass,
      'className' => $className,
      'dependencies' => []
    );
    $constructor = $rflClass->getConstructor();
    $properties = $rflClass->getProperties(\ReflectionProperty::IS_PUBLIC);

    if($constructor !== null && is_array($constructor->getParameters()) && count($constructor->getParameters()) > 0) {
      $params = $constructor->getParameters();
      foreach($params as $param) {
        
        $dependencyType = isset($param->info['type_hint']) ? $param->info['type_hint'] :
          (isset($param->info['type']) ? $param->info['type'] : null);
        
        if(empty($dependencyType)){
          continue;
        }

        $dependencyType = ltrim($dependencyType, '?'); // optional dependencies
        array_push($map['dependencies'], $dependencyType);
      }
    } else if(is_array($properties) && count($properties) > 0){
      foreach($properties as $property) {
        $type = $property->getTypeText();
        if(empty($type)) {
          continue;
        }
        array_push($map['dependencies'], $type);
      }
    }
    return $this->metadata[$className] = $map;
  }

  public function make(string $className)
  {
    $this->registered[$className] = true;
    return $this->get($className);
  }

  protected static function createInstance(IContainer $ioc, string $className, InjectionScope $scope)
  {
    $hydrator = $ioc->getHydrator($className);
    return $hydrator->get();
    
    $rflClass = new \ReflectionClass($className);
    $instance = $rflClass->newInstanceWithoutConstructor();
    switch($scope) {
      case InjectionScope::Constructor:
        self::injectDependenciesByConstructor($ioc, $instance, $rflClass);
        break;
      case InjectionScope::Public:
        self::injectDependenciesByPublicProperties($ioc, $instance, $rflClass);
        break;
    }
    return $instance;
  }

  

  protected static function injectDependenciesByPublicProperties(IContainer $ioc, &$instance, \ReflectionClass $rflClass)
  {
    
    $properties = $rflClass->getProperties(\ReflectionProperty::IS_PUBLIC);
    
    foreach($properties as $property) {
      $type = $property->getTypeText();
      if(!is_string($type)) {
        continue;
      }

      if(get_parent_class($type) === 'Pi\Odm\MongoRepository') {
        $repoInstance = $ioc->get($type);
        $property->setValue($instance, $repoInstance);
      }
      else {
        $dependency = $ioc->get($type);
        if($dependency === null) continue;
        $property->setValue($instance, $dependency);
      }
    }
  }

  protected static function injectDependenciesByConstructor(IContainer $ioc, &$instance, \ReflectionClass $rflClass)
  {
    $constructor = $rflClass->getConstructor();

    if($constructor === null || !is_array($constructor->getParameters()) || count($constructor->getParameters()) == 0) {
      return;
    }

    $params = $constructor->getParameters();
    
    foreach($params as $param) {
      $name = $param->info['name'];
      $dependencyType = isset($param->info['type_hint']) ? $param->info['type_hint'] :
        (isset($param->info['type']) ? $param->info['type'] : null);
      
      if($dependencyType === null){
        continue;
      }

      $dependencyType = ltrim($dependencyType, '?'); // optional dependencies

      $dependency = $ioc->get($dependencyType);
      if($dependency === null) {
        continue;
      }
      $instance->$name = $dependency;
    }
  }
}
