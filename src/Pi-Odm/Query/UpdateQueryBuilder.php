<?hh

namespace Pi\Odm\Query;
use Pi\Odm\Mapping\EntityMetaData;
use Pi\Odm\UnitWork;
use Pi\Odm\MongoManager;

class UpdateQueryBuilder extends QueryBuilder {

  protected $reflClass;
  protected $class;

  public function __construct(
    protected $entity,
    protected UnitWork $unitWork,
    protected MongoManager $mongoManager)
  {


    $this->setDocument(get_class($entity));
    $this->class = $this->mongoManager->getClassMetadata(get_class($entity));
    $this->reflClass = $this->class->getReflectionClass();

  }

  public function createQuery()
  {
    $className = get_class($this->entity);
    $builder = new QueryBuilder($this->unitWork, $this->mongoManager, $className);
    $builder->update();


    foreach($this->class->mappings() as $fieldName => $mapping) {

      $f = $mapping->getFieldName();

      if($this->reflClass->hasProperty($f)) {


        /*
        if($mapping->isEmbedOne()){
          continue;
        }
        if($mapping->isEmbedMany()) {
          continue;
        }
        if($mapping->isReferenceOne()){
          continue;
        }
        if($mapping->isReferenceMany()){
          continue;
        }
        if($mapping->isDBRef()) {
          continue;
        }*/
        if($fieldName === $this->class->getId()){
          $builder->field($fieldName)->eq($this->entity->$fieldName());
        } else {
          if(isset($this->entity->$f)) {
            $builder->field($fieldName)->set($this->entity->$f());  
          }

        }

  			/*$reflProp = $this->reflClass->getProperty($mapping->getFieldName());

  			$reflProp->setAccessible(true);*/
  		}
    }
    return $builder->getQuery();
  }
}
