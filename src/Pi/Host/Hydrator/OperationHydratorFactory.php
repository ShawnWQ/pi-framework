<?hh

namespace Pi\Host\Hydrator;

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
use Pi\Host\OperationMetaFactory;

/**
 * Operations Hydrator Factory
 * Hydrates data from $_GET, $_PUT, etc into Operations classes
 * Hydrators are generated if necessary, always per Operation
 */
class OperationHydratorFactory implements IContainable {

  protected $hydrators = Map {};

  protected $ioc;

  public function __construct(
    protected OperationMetaFactory $operationFactory,
    protected EventManager $eventManager,
    protected string $hydratorNamespace,
    protected string $hydratorDir
    )
  {

  }

  /**
   * Hydrate array of $_POST, $_GET, $_PUT, etc data into the given class
   * @param  [type] $document [description]
   * @param  [type] $data     [description]
   * @return [type]           [description]
   */
  public function hydrate($document, $data)
  {

    if(is_null($data) || !is_array($data)){
      throw new \Exception('The $data passed to hydrator factory must be an array');
    }
    $class = $this->operationFactory->getClassMetadata(get_class($document));

    $data = $this->getForEntity($document)->hydrate($data, $document);
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

    $classMetaData = $this->operationFactory->getClassMetadata($className); // get from DM

    // Check if class exists but dont load it.
    if(!class_exists($fn, false)){
      // If dont exist the hydrator class wasnt generated it
      $fileName = $this->hydratorDir . DIRECTORY_SEPARATOR . $hydratorClass . '.php';
      $this->generateHydratorClass($classMetaData,$hydratorClass, $fileName);
    }

    $this->hydrators[$className] = new $fn($this->unitWork, $this->mongoManager, $classMetaData);

    return $this->hydrators[$className];
  }

  /**
   * Generates a Hydrator for a specific document and saves it
   * @param EntityMetaData $entity            [description]
   * @param [type]         $hydratorClassName [description]
   * @param [type]         $fileName          [description]
   */
  public function generateHydratorClass(EntityMetaData $entity, $hydratorClassName, $fileName)
  {
    $hydratorNamespace = $this->hydratorNamespace;// . '\\' . $entity->getName();
    $code = '';



    foreach($entity->mappings() as $key => $mapping){
      //if($mapping->isString()) {
      $fieldName = $mapping['fieldName'];
      $name = $mapping['name'];

      if($mapping['embedMany']) {
        $embedType = $mapping->getEmbedType();
                      $code .= sprintf(<<<EOF
       if (array_key_exists('$name', \$params)) {

          \$many = array();
          foreach(\$params['$name'] as \$value) {
            \$many[] = \$this->serviceMetadata->getDto('$embedType', \$value);
          }

          \$this->class->reflFields['$name']->setValue(\$document, \$many);
          \$hydratedData['$name'] = \$many;
       } else if(property_exists('$name', \$body)) {
          \$many = array();
          foreach(\$body['$name'] as \$value) {
            \$many[] = \$this->serviceMetadata->getOperation('$embedType', \$value);
          }

          \$this->class->reflFields['$name']->setValue(\$document, \$many);
          \$hydratedData['$name'] = \$many;
       }
EOF

                 );
      } else if($entity->getId() === $name) {

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

use Pi\Host\ServiceMetadata;
use Pi\Odm\MongoManager;
use Pi\Host\Operation;
use Pi\Odm\Interfaces\IHydrator;
use Pi\Odm\Interfaces\IEntity;
/**
 * Hydrator class generated by Pi framework
 */
class $hydratorClassName implements IHydrator
{
    public function __construct(protected ServiceMetadata \$serviceMetadata, protected Operation \$class)
    {

    }

    public function extract(IEntity \$object){}

    public function hydrate(array \$params, array $body, \$document)
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
