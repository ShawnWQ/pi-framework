<?hh

namespace Pi\Odm\Mapping\Driver;

use Pi\Odm\Mapping\EntityMetaData;
use Pi\Odm\Mapping\EntityFieldMapping;
use Pi\Odm\MappingType;
use Pi\Odm\CascadeOperation;
use Pi\Odm\Events;
use Pi\Odm\Interfaces\IMappingDriver;
use Pi\Common\ClassUtils;
use Pi\Common\Mapping\Driver\AbstractMappingDriver;
use Pi\Host\HostProvider;

/**
 * The AttributeDriver reads the metadata from hacklang attributes
 */
class AttributeDriver extends AbstractMappingDriver implements IMappingDriver{

  public static function create($paths = array())
  {
    return new self($paths);
  }


  public function loadMetadataForClass(string $className, EntityMetaData $entity)
  {

    $reflClass = $entity->getReflectionClass();

    $attrs = Map{};
    $parent =  $reflClass->getParentClass();
    if($parent) {
      foreach($parent->getAttributes() as $key => $value) {
        $attrs[$key] = $value;
      }
    }
    foreach($reflClass->getAttributes() as $key => $value) {
      $attrs[$key] = $value;
    }


    // get attributes
    $attr = $reflClass->getAttribute('Collection');
    if($attr !== null) {

      $entity->setCollection($attr[0]);
    }
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



    /*$value = $reflClass->getAttribute(MappingType::DefaultDiscriminatorValue);
    if($value !== null) {
      $entity->setDefaultDiscriminatorValue($value[0]);
    }*/

    $value = $attrs->get(MappingType::InheritanceType);

    if($value !== null) {

      $entity->setInheritanceType($value[0]);
    }

    $value = $attrs->get(MappingType::DiscriminatorField);
    if($value !== null) {
      $type = is_null($reflClass->getAttribute(MappingType::InheritanceType)) ? 'Single' : $reflClass->getAttribute(MappingType::InheritanceType)[0];
      $entity->setDiscriminator($value[0], $type);
    }

    $multiT = HostProvider::instance()->tryResolve('OdmConfiguration')->getMultiTenantMode();


    if($multiT && $attr = $reflClass->getAttribute('MultiTenant') !== NULL) {

      $entity->setMultiTenant(true);
      $multiT = true;

      if($entity->getReflectionClass()->hasProperty('appId')) {
        //$appIdProp = $entity->getReflectionClass()->getProperty('appId');
        $entity->setMultiTenantField('appId');
        
      }
    }

    // Lead with top level document attributes


    // Map Properties
    foreach($reflClass->getProperties() as $property){
      // Loop attributos para ela

    }

    $m = $reflClass->getMethods(\ReflectionMethod::IS_PUBLIC);

    if($parent) {
        foreach($parent->getMethods(\ReflectionMethod::IS_PUBLIC) as $d) {

          $m[] = $d;
        }
    }

    // Lifecycle events through mehtods
    foreach ($m as $method) {

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
      $mapping = new EntityFieldMapping();

        $methodName = ClassUtils::getMethodName($method->getName());

        $mapping->setFieldName($methodName);
        $isMapping = true;

      foreach($method->getAttributes() as $key => $value) {

        switch($key){
          case MappingType::Id:
            $entity->setId(ClassUtils::getMethodName($method->getName()));
          break;

          case MappingType::Timestamp:
            $mapping->setTimestamp(true);
            break;

          case MappingType::NotNull:
            $mapping->setIsNotNull(true);
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

          case MappingType::DateTime:
            $mapping->setDateTime();
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

  public function readAttributesProperty($property)
  {

  }

  public function readAttributesMethod($method)
  {
    return $method->getAttributes();
  }
}
