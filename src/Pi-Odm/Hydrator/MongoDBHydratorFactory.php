<?hh

namespace Pi\Odm\Hydrator;
use Pi\Common\ClassUtils;
use Pi\Odm\MongoConnection;
use Pi\Odm\Interfaces\IEntity;
use Pi\Odm\MongoEntity;
use Pi\AppHost;
use Pi\EventManager;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;
use Pi\Odm\Events;
use Pi\Odm\UnitWork;
use Pi\Odm\Mapping\EntityMetaData;
use Pi\Odm\OdmConfiguration;
use Pi\Odm\Interfaces\IEntityMetaDataFactory;

/**
 * Mongo Hydrator Factory
 * Similar to Doctrine ODM hydrators
 */
class MongoDBHydratorFactory implements IContainable {

  protected $hydrators = Map {};

  protected $ioc;

  protected $connection;


  protected $hydratorDir;

  protected $hydratorNamespace;

  protected $mongoManager;

  public function __construct(
    protected OdmConfiguration $configuration,
    protected IEntityMetaDataFactory $entityMetaDataFactory,
    protected EventManager $eventManager,
    protected UnitWork $unitWork)
  {

    $this->mongoManager = $unitWork->mongoManager;

    //$this->entityMetaDataFactory = $unitWork->classMetaDataFactory();
    $this->hydratorDir = $configuration->getHydratorDir();

    $this->hydratorNamespace = $configuration->getHydratorNamespace();

    if(empty($this->hydratorDir)){
      throw new \Exception('The MongoHydratorFactory requires a valid $hydratorDir to save the hydrated files');
    }

    if(empty($this->hydratorNamespace)){
      throw new \Exception('The $hydratorNamespace cant be empty, its required for autoloader');
    }

  }

  /**
   * Hydrate array of MongoDB document data into the given document object
   * @param  [type] $document [description]
   * @param  [type] $data     [description]
   * @return [type]           [description]
   */
  public function hydrate(IEntity $document, $data)
  {

    if(is_null($data) || !is_array($data)){
      throw new \Exception('The $data passed to hydrator factory must be an array');
    }
    $class = $this->mongoManager->getClassMetadata(get_class($document));

    if($class->hasLifeCycleCallbacks(Events::PreLoad)){
      $args = array(&$data);
      $class->invokeLifecycleCallbacks(Events::PreLoad, $document, $args);
    }

    $data = $this->getForEntity($document)->hydrate($data, $document);
  }

  public function ioc(IContainer $container)
  {

    $this->ioc = $container;



  }

  public function getForEntity(IEntity $entity)
  {
    return $this->get(get_class($entity));
  }

  public function get($className)
  {

    if($this->hydrators->contains($className)){
      // Already in memory
      return $this->hydrators[$className];
    }


    $hydratorClass = str_replace('\\', '', ClassUtils::getClassRealname($className)) . 'Hydrator';
    $fn = $this->hydratorNamespace . '\\' . $hydratorClass;

    $classMetaData = $this->mongoManager->getClassMetadata($className); // get from DM

    $fileName = $this->hydratorDir . DIRECTORY_SEPARATOR . $hydratorClass . '.php';

    // Check if class exists but dont load it.
    if(!class_exists($fn, false)){
      $this->generateHydratorClass($classMetaData,$hydratorClass, $fileName);
    }

    //try {
      $this->hydrators[$className] = new $fn($this->unitWork, $this->mongoManager, $classMetaData);
//    } catch(\Exception $ex) {
  //    throw new \Exception(sprintf('Couldnt load the class %s. Filename used: %s', $hydratorClass, $fileName));
    //}

    return $this->hydrators[$className];
  }

  /**
   * Generates a Hydrator for a specific document and saves it
   */
  public function generateHydratorClass(EntityMetaData $entity, $hydratorClassName, $fileName)
  {
    $hydratorNamespace = $this->hydratorNamespace;
    $code = '';

    foreach($entity->mappings() as $key => $mapping){
      $fieldName = $mapping->getFieldName();
      $name = $mapping->getName();

      if($mapping->isEmbedMany()) {
        $embedType = $mapping->getEmbedType();
                      $code .= sprintf(<<<EOF
       if (array_key_exists('$name', \$data) && is_array(\$data['$name'])) {

          \$many = array();
          foreach(\$data['$name'] as \$r) {
            if(array_key_exists('id', \$r) && !array_key_exists('_id', \$r)) {
              \$r['_id'] = \$r['id'];
            } else if(array_key_exists('_id', \$r) && !array_key_exists('id', \$r)) {
              \$r['id'] = \$r['_id'];
            }

            \$many[] = \$this->unitOfWork->getDocument('$embedType', \$r);
          }
          if(is_null(\$many)) continue;
          \$this->class->reflFields['$name']->setValue(\$document, \$many);
          \$hydratedData['$name'] = \$many;

       }
EOF

                 );
      }  else if($mapping->getIsInt()) {
                      $code .= sprintf(<<<EOF
      if(array_key_exists('$name', \$data) && is_int(\$data['$name'])){
          \$r = \$data['$name'];
          
          \$this->class->reflFields['$name']->setValue(\$document, \$r);
          \$hydratedData['$name'] = \$data['$name'];
       }
EOF

                 );
                    }

      else if($mapping->isEmbedOne()) {
        $embedType = $mapping->getEmbedType();
                      $code .= sprintf(<<<EOF
       if (array_key_exists('$name', \$data) && !is_null(\$data['$name'])) {
          \$many = \$this->unitOfWork->getEmbedDocument('$embedType', \$data['$name']);
          if(is_null(\$many)) continue;
          \$this->class->reflFields['$name']->setValue(\$document, \$many);
          \$hydratedData['$name'] = \$many;
       }
EOF

                 );
      }
      else if($entity->getId() === $name) {

                 $code .= sprintf(<<<EOF
       if (array_key_exists('$name', \$data)) {
          \$r = \$data['$name'];
          \$this->class->reflFields['$name']->setValue(\$document, \$r);
          \$hydratedData['$name'] = \$data['$name'];
       } else if (array_key_exists('_id', \$data)) {
          \$r = \$data['_id'];
          \$this->class->reflFields['$name']->setValue(\$document, \$r);
          \$hydratedData['_id'] = \$data['_id'];

       }
EOF
          );

      } else if($mapping->isDateTime()) {

                 $code .= sprintf(<<<EOF
       if (array_key_exists('$name', \$data)) {

        try {

          \$r = array_key_exists('date', \$data['$name']) ? new \MongoDate(\$data['$name']['date']) : new \MongoDate(\$data['$name']);
          \$d = new \DateTime(\$r->sec);
          \$this->class->reflFields['$name']->setValue(\$document, \$d->getTimestamp());
          \$hydratedData['$name'] = \$data['$name'];
          }
          catch(\Exception \$ex) {
            \$r = \$data['$name'];
            
          }
       }
EOF
          );

      } else {

              $code .= sprintf(<<<EOF

       if (array_key_exists('$name', \$data)) {
          \$r = \$data['$name'];
          \$this->class->reflFields['$name']->setValue(\$document, \$r);
          \$hydratedData['$name'] = \$data['$name'];
       }

EOF
                 );
      }


    }

    $code = sprintf(<<<EOF
<?hh

namespace $hydratorNamespace;

use Pi\Odm\UnitWork;
use Pi\Odm\MongoManager;
use Pi\Odm\Mapping\EntityMetaData;
use Pi\Odm\Interfaces\IHydrator;
use Pi\Odm\Interfaces\IEntity;
/**
 * ODM Hydrator class 
 * Generated by Pi Framework
 */
class $hydratorClassName implements IHydrator
{
    /**
     * @var MongoManager
     */
    private \$dm;
    /**
     * @var UnitWork
     */
    private \$unitOfWork;
    /**
     * @var EntityMetaData
     */
    private \$class;

    public function __construct(UnitWork \$unitWork, MongoManager \$dm, EntityMetaData \$class)
    {
        \$this->dm = \$dm;
        \$this->unitOfWork = \$unitWork;
        \$this->class = \$class;
    }

    public function extract(IEntity \$object){}


    public function hydrate(array \$data, IEntity \$document,)
    {
        \$hydratedData = array();
%s        return \$hydratedData;
    }
}
EOF
            ,
            $code
        );

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

}