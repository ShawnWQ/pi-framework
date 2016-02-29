<?hh

namespace Pi\Odm\Mapping\Driver;

use Pi\Odm\Mapping\EntityMetaData,
    Pi\Odm\Mapping\EntityFieldMapping,
    Pi\Odm\MappingType,
    Pi\Odm\CascadeOperation,
    Pi\Odm\Events,
    Pi\Odm\Interfaces\IMappingDriver,
    Pi\Common\ClassUtils,
    Pi\Common\Mapping\Driver\AbstractMappingDriver,
    Pi\Host\HostProvider,
    Pi\EventManager,
    Pi\Interfaces\ICacheProvider,
    Pi\Interfaces\DtoMetadataInterface;




/**
 * The AttributeDriver reads the metadata from hacklang attributes
 */
class AttributeDriver extends AbstractMappingDriver implements IMappingDriver{


  public static function create($paths = array(), EventManager $em, ICacheProvider $cache)
  {
    return new self($paths, $em, $cache);
  }

  public function loadMetadataForClass(string $className, DtoMetadataInterface $entity)
  {
    if(!$entity instanceof EntityMetaData) {
      throw new \Exception('Attribute driver only reads EntityMetadata for ODM');
    }

    $reflClass = $entity->getReflectionClass();
    $parent =  $reflClass->getParentClass();
    $this->mapBaseMappings($entity, $reflClass);
    $methods = $this->getClassMethods($entity);


    $attr = $reflClass->getAttribute('Collection');
    if($attr !== null) {
      $entity->setCollection($attr[0]);
    }

    foreach ($methods as $method) {

      if ($method->getDeclaringClass()->name !== $reflClass->name) {
         //continue;
      }
      if(count($method->getAttributes()) === 0) {
        continue;
      }

      $mapping = new EntityFieldMapping();
      $methodName = ClassUtils::getMethodName($method->getName());
      $mapping->setFieldName($methodName);
      $isMapping = true;

      foreach($method->getAttributes() as $key => $value) {

        switch($key){
          case MappingType::Id:
            $entity->setId(ClassUtils::getMethodName($method->getName()));
          break;

          case MappingType::EmbedOne:
            $mapping->setEmbedOne();
            $mapping->setEmbedType($value[0]);
          break;

          case MappingType::EmbedMany:
            $mapping->setEmbedMany($value[0]);
          break;

          case MappingType::DBRef:
            $mapping->setDBRef();
          break;

          case MappingType::ReferenceOne:
            $mapping->setReferenceOne();
          break;

          case MappingType::ReferenceMany:
              $mapping->setReferenceMany();
          break;

          case MappingType::DefaultDiscriminatorValue:
            $mapping->setDefaultDiscriminatorValue($value[0]);
          break;

          case MappingType::InheritanceType:
            $mapping->setInheritanceType($value[0]);
          break;

          case MappingType::DiscriminatorField:
            $mapping->setDiscriminatorField($value[0]);
          break;

          case Events::PreUpdate:
            $entity->addLifeCycleCallback($method->getName(), Events::PreUpdate);
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

    $this->mapBaseEntityAttributes($entity, $reflClass);
  }
  

  public function readAttributesProperty($property)
  {

  }

  public function readAttributesMethod($method)
  {
    return $method->getAttributes();
  }
}
