<?hh

namespace Pi\Host;

use Pi\HostConfig;
use Pi\Host\ServiceController;
use Pi\Host\Mapping\OperationMappingType;
use Pi\Common\ClassUtils;
use Pi\Odm\MongoConnection;
use Pi\Odm\Interfaces\IEntity;
use Pi\Odm\MongoEntity;
use Pi\AppHost;
use Pi\EventManager;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;
use Pi\Odm\Events;
use Pi\Host\Operation;;
use Pi\Odm\OdmConfiguration;
use Pi\Odm\Interfaces\IEntityMetaDataFactory;

/**
 * Mongo Hydrator Factory
 * Similar to Doctrine ODM hydrators
 */
class OperationHydratorFactory implements IContainable {

  protected $hydrators = Map {};

  protected $ioc;

  protected $connection;


  protected $hydratorDir;

  protected $hydratorNamespace;

  public function __construct(
    protected HostConfig $configuration,
    protected IEntityMetaDataFactory $entityMetaDataFactory,
    protected EventManager $eventManager,
    protected $serviceController)
  {
    $this->hydratorDir = $configuration->hydratorDir();

    $this->hydratorNamespace = $configuration->getHydratorNamespace();

    if(empty($this->hydratorDir)){
      throw new \Exception('The OperationHydratorFactory requires a valid $hydratorDir to save the hydrated files');
    }

    if(empty($this->hydratorNamespace)){
      throw new \Exception('The hydrator namespace cant be empty, its required for autoloader');
    }

  }

  public function hydrate($document, $data)
  {

    if(is_null($data) || !is_array($data)){
      throw new \Exception('The $data passed to hydrator factory must be an array');
    }
    $class = $this->serviceController->getClassMetadata(get_class($document));

/*    if($class->hasLifeCycleCallbacks(Events::PreLoad)){
      $args = array(&$data);
      $class->invokeLifecycleCallbacks(Events::PreLoad, $document, $args);
    }*/

    //die('rfl::: '.print_r($class->reflClass));

    $data = $this->getForEntity($document)
                 ->hydrate($data, $document);
  }

  public function ioc(IContainer $container)
  {

    $this->ioc = $container;



  }

  public function getForEntity($entity)
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

    $classMetaData = $this->serviceController->getClassMetadata($className); // get from DM

    // Check if class exists but dont load it.
    if(!class_exists($fn, false)){
      // If dont exist the hydrator class wasnt generated it
      $fileName = $this->hydratorDir . DIRECTORY_SEPARATOR . $hydratorClass . '.php';
      $this->generateHydratorClass($classMetaData,$hydratorClass, $fileName);
      if($this->eventManager->has(HostEvents::PostGenerateHydrator)){
        $this->eventManager->dispatch(HostEvents::PostGenerateHydrator, new PostPostGenerateHydratorArgs($className, $classMetaData));
      }
    }


    $this->hydrators[$className] = new $fn($this->serviceController, $classMetaData);

    return $this->hydrators[$className];
  }

  /**
   * Generates a Hydrator for a specific document and saves it
   * @param EntityMetaData $entity            [description]
   * @param [type]         $hydratorClassName [description]
   * @param [type]         $fileName          [description]
   */
  public function generateHydratorClass(Operation $entity, $hydratorClassName, $fileName)
  {
    $hydratorNamespace = $this->hydratorNamespace;// . '\\' . $entity->getName();
    $code = '';

    foreach($entity->mappings() as $key => $mapping){
      //if($mapping->isString()) {
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

            \$many[] = \$this->serviceController->getDocument('$embedType', \$r);
          }
          if(is_null(\$many)) continue;
          \$this->class->reflFields['$name']->setValue(\$document, \$many);
          \$hydratedData['$name'] = \$many;

       }
EOF

                 );
      }  else if($mapping->isInt() === OperationMappingType::Int) {
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
          \$many = \$this->serviceController->getEmbedDocument('$embedType', \$data['$name']);
          if(is_null(\$many)) continue;
          \$this->class->reflFields['$name']->setValue(\$document, \$many);
          \$hydratedData['$name'] = \$many;
       }
EOF

                 );
      }
      else if(array_key_exists('id', $mapping) && $mapping['id'] === $name) {

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

      } else if($mapping->getPHPType() === OperationMappingType::DateTime) {

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
          \$this->class->reflFields['$name']->setValue(\$document, \$r);
          }
       }
EOF
          );

      } else {

              $code .= sprintf(<<<EOF

       if (array_key_exists('$name', \$data)) {
          \$r = \$data['$name'];
          \$this->class->reflClass->getProperty('$name')->setValue(\$document, \$r);
          \$hydratedData['$name'] = \$data['$name'];
       }

EOF
                 );
      }


    }

    $code = sprintf(<<<EOF
<?hh

namespace $hydratorNamespace;

use Pi\Host\ServiceRegistry;
use Pi\Host\Operation;
use Pi\Interfaces\IOperationHydrator;
/**
 * Hydrator class generated by Pi framework
 */
class $hydratorClassName implements IOperationHydrator
{
    /**
     * @var ServiceRegistry
     */
    private \$serviceController;
    /**
     * @var Operation
     */
    private \$class;

    public function __construct(ServiceRegistry \$serviceController, Operation \$class)
    {
        \$this->serviceController = \$serviceController;
        \$this->class = \$class;
    }

    public function extract(\$object){}


    public function hydrate(array \$data, \$document,)
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
     $r = file_put_contents($tmpFileName, $code);

    // rename($tmpFileName, $fileName);
    if( copy($tmpFileName, $fileName) ) {
      unlink($tmpFileName);
    }
  }

}
