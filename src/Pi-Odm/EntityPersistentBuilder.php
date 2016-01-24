<?hh

namespace Pi\Odm;


use Pi\Common\StringUtils;
/**
 * Builds the queries used by EntityPersistent to update and insert documents then a DocumentMananger is flushed
 */
class EntityPersistentBuilder {

  public function __construct(protected MongoManager $mongoManager, protected UnitWork $unitWork)
  {

  }

  public function prepareUpdate($document)
  {
    $class = $this->mongoManager->getClassMetadata(get_class($document));
  }

  /**
   * Used for push embed docs
   */
  public function prepareBuildData($document)
  {
   $class = $this->mongoManager->getClassMetadata(get_class($document));
    $changeset = $this->unitWork->getDocumentChangeSet($document);

    $insertions = array();
    // Generate Id
    $id = new \MongoId();
    if(!$class->isEmbeddedDocument()) {
      $class->setIdentifierValue($id, $document);
    }

    foreach($class->mappings() as $mapping) {
      $new = isset($changeset[$mapping->getFieldName()][1]) ? $changeset[$mapping->getFieldName()][1] : null;
       // check if mapping allow nullable for $new === null

       $value = null;

       if($class->getReflectionClass()->hasProperty($mapping->getFieldName())) {
          $prop = $class->getReflectionClass()->getProperty($mapping->getFieldName());
          $prop->setAccessible(true);
          $value = $prop->getValue($document);
        }

       if($mapping->isArray() && is_array($value) && count($value) === 0)
       {
          $insertions[$mapping->getName()] =  null;
       } else if($mapping->isEmbedMany() && count($value) === 0) {

       } else if($mapping->getName() == $class->getId() && $mapping->getName() === 'id') {
          $insertions['_id'] = $value;
       }
      else {
        $insertions[$mapping->getName()] = $value;
      }
    }

    if($class->getMultiTenantMode()){
      $reflection = new \ReflectionProperty(get_class($document), $class->getMultiTenantField());
      $reflection->setAccessible(true);
      $insertions['appId'] = $reflection->getValue($document);
    }

    if(!StringUtils::isNullOrEmptyString($class->getDiscriminatorField())) {
      $insertions[$class->getDiscriminatorField()] = get_class($document);
    }

    return $insertions;
  }

  public function prepareInsertData($document)
  {
    $docClass = get_class($document);
    $class = $this->mongoManager->getClassMetadata($docClass);

    $changeset = $this->unitWork->getDocumentChangeSet($document);

    $insertions = array();


    foreach($class->mappings() as $mapping){
      $field = $mapping->getFieldName();

      $new = isset($changeset[$field][1]) ? $changeset[$mapping->getFieldName()][1] : null;

       // check if mapping allow nullable for $new === null

       $value = null;

       if($new !== null){

         //$value = Type::getType($mapping['type'])->convertToDatabaseValue($new);
         $value = $new;
       }
     if($class->getReflectionClass()->hasProperty($mapping->getFieldName())) {
        $prop = $class->getReflectionClass()->getProperty($mapping->getFieldName());
        $prop->setAccessible(true);
        $value = $prop->getValue($document);
      }
     if($mapping->isNotNull() && $mapping->isArray() && (!is_array($value) || count($value) === 0))
     {
        $insertions[$mapping->getName()] = array();

     } else if($mapping->isEmbedMany() && count($value) === 0) {

     }
      else if($mapping->getName() == $class->getId()){ // // Id Generator - write customer generators based on field type
        if(is_null($value)) {
          // Generate Id
          $id = new \MongoId();
          $class->setIdentifierValue($id, $document);
          $value = $id;
        }
        $insertions['_id'] = $value;
      } else {

        if($mapping->getName() === 'paymentId') {

        }
        if($field === 'type') {

        }
        if(!is_null($value) || (is_null($value) && $mapping->isNotNull()))
          $insertions[$mapping->getName()] = $value;
      }
    }
    if($class->getMultiTenantMode()){
      $reflection = new \ReflectionProperty(get_class($document), $class->getMultiTenantField());
      $reflection->setAccessible(true);
      $insertions['appId'] = $reflection->getValue($document);
    }

    if(!StringUtils::isNullOrEmptyString($class->getDiscriminatorField()) && !array_key_exists($class->getDiscriminatorField(), $insertions)) {
      $insertions[$class->getDiscriminatorField()] = get_class($document);
    }

    return $insertions;
  }
}
