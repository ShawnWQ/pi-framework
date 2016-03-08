<?hh

namespace Pi\Common\Mapping\Driver;

use Pi\EventManager,
    Pi\Interfaces\IContainable,
    Pi\Interfaces\ICacheProvider,
    Pi\Interfaces\IContainer,
    Pi\Interfaces\DtoMetadataInterface,
    Pi\Odm\MappingType,
    Pi\Odm\Interfaces\IMappingDriver,
    Pi\Common\Mapping\ClassFieldMapping,
    Pi\Common\ClassUtils,
    Pi\Host\HostProvider;




abstract class AbstractMappingDriver implements IContainable, IMappingDriver {

    
  public function __construct(protected array $paths = array(), EventManager $em, ICacheProvider $cache)
  {

  }

	public function loadMetadataForClass(string $className, DtoMetadataInterface $entity)
	{
		$reflClass = $entity->getReflectionClass();
		$parent =  $reflClass->getParentClass();
		$this->mapBaseMappings($entity, $reflClass);
		$methods = $this->getClassMethods($entity);
		$this->mapBaseEntityAttributes($entity, $reflClass);
	}

  public function ioc(IContainer $container){
    $this->paths = array();
  }


  protected function getClassAttributes(DtoMetadataInterface $entity, bool $parent = true) : Map
  {
    $reflClass = $entity->getReflectionClass();

    $attrs = Map{};
    $parent =  $reflClass->getParentClass();
    
    if($parent) { // parent attributes first
      foreach($parent->getAttributes() as $key => $value) {
        $attrs[$key] = $value;
      }
    }
    foreach($reflClass->getAttributes() as $key => $value) { // the class attributes
      $attrs[$key] = $value;
    }
    return $attrs;
  }

  protected function getClassMethods(DtoMetadataInterface $entity, bool $parent = true)
  {
    $reflClass = $entity->getReflectionClass();
    $parent =  $reflClass->getParentClass();
    $methods = $reflClass->getMethods(\ReflectionMethod::IS_PUBLIC);

    if($parent) {
        foreach($parent->getMethods(\ReflectionMethod::IS_PUBLIC) as $d) {
          $methods[] = $d;
        }
    }
    return $methods;
  }

  protected function mapBaseMappings(DtoMetadataInterface $entity, ?\ReflectionClass $reflClass = null) : void
  {
    if($reflClass == null) {
      $reflClass = $entity->getReflectionClass();  
    }
    
    $parent =  $reflClass->getParentClass();
    $methods = $this->getClassMethods($entity);

    foreach ($methods as $method) {

      /* Filter for the declaring class only. Callbacks from parent
       * classes will already be registered.
       */
      if ($method->getDeclaringClass()->name !== $reflClass->name) {
         //continue;
      }
      if(count($method->getAttributes()) === 0) {
        continue;
      }

      // if(não existe attributo para haver lifecycles, continue)
      $mapping = new ClassFieldMapping();

      $methodName = ClassUtils::getMethodName($method->getName());

      $mapping->setFieldName($methodName);
      $isMapping = true;

      foreach($method->getAttributes() as $key => $value) {

        switch($key){

          case MappingType::Timestamp:
            $mapping->setTimestamp();
            break;

          case MappingType::NotNull:
            $mapping->setIsNotNull();
            break;

          case MappingType::Collection:
            $mapping->setArray();
          break;

          case MappingType::String:
            $mapping->setString();
          break;

          case MappingType::Int:
            $mapping->setIsInt();
          break;

          case MappingType::DateTime || MappingType::Date:
            $mapping->setDateTime();
            break;

          case 'ObjectId':

          break;

          default:
            $isMapping = false;
          break;
        }
      }
      if($isMapping) {
        $entity->mapField($mapping);
      }
    }
  }

  protected function mapBaseEntityAttributes(DtoMetadataInterface $entity, ?\ReflectionClass $reflClass = null) : void
  {
    if($reflClass == null) {
      $reflClass = $entity->getReflectionClass();  
    }

    $attrs = $this->getClassAttributes($entity);
    
    if(!is_null($reflClass->getAttribute('DiscriminatorField'))) {
      $type = is_null($reflClass->getAttribute('InheritanceType')) ? 'Single' : $reflClass->getAttribute('InheritanceType')[0];
      $value = is_null($reflClass->getAttribute('DefaultDiscriminatorValue')) ? 'default' : $reflClass->getAttribute('DefaultDiscriminatorValue')[0];
      $entity->setDiscriminator($reflClass->getAttribute('DiscriminatorField')[0], $type, $value);
    }

    $value = $reflClass->getAttribute('EmbeddedDocument');
    if($value !== null) {
      $entity->setEmbeddedDocument();
    }

    $value = $attrs->get(MappingType::MappedSuperclass);
    if($value !== null) {
      $entity->setMappedSuperclass(true);
    }

    $value = $attrs->get(MappingType::InheritanceType);

    if($value !== null) {

      $entity->setInheritanceType($value[0]);
    }

    $value = $attrs->get(MappingType::DiscriminatorField);
    if($value !== null) {
      $type = is_null($reflClass->getAttribute(MappingType::InheritanceType)) ? 'Single' : $reflClass->getAttribute(MappingType::InheritanceType)[0];
      $entity->setDiscriminator($value[0], $type);
    }

    $multiT = HostProvider::tryInstance()?->tryResolve('OdmConfiguration')?->getMultiTenantMode();

    if($multiT != null && $attr = $reflClass->getAttribute('MultiTenant') !== NULL) {

      $entity->setMultiTenant(true);
      $multiT = true;

      if($entity->getReflectionClass()->hasProperty('appId')) {
        //$appIdProp = $entity->getReflectionClass()->getProperty('appId');
        $entity->setMultiTenantField('appId');
        
      }
    }
  }
}
