<?hh

namespace Pi\Redis;

interface HydratorFactoryInterce {

	public function hydrate($document, $data);

	public function generateHydratorClass(AbstractMetadata $entity, $hydratorClassName, $fileName);
}


class RedisHydractorFactory extends AbstractHydractorFactory {


}
class AbstractHydractorFactory implements HydratorFactoryInterce {
	
	protected Map $hydrators;
	
	public function __construct(
		protected IEntityMetaDataFactory $entityMetadataFactory,
		protected string $hydratorNamespace,
		protected string $hydratorDir
		)
	{

		if(empty($this->hydratorDir)){
	      throw new \Exception('The MongoHydratorFactory requires a valid $hydratorDir to save the hydrated files');
	    }

	    if(empty($this->hydratorNamespace)){
	      throw new \Exception('The $hydratorNamespace cant be empty, its required for autoloader');
	    }

	    $this->hydrators = Map{};
	}

	public function hydrate($document, $data)
	{
		if(is_null($data) || !is_array($data)){
	      throw new \Exception('The $data passed to hydrator factory must be an array');
	    }

	    $metadata = $this->getMetadataFor(get_class($document));
	    if($metadata == null || !$metadata instanceof AbstractMetadata) {
	    	throw new \Exception("Metadata not resolved");
	    }

	}

	protected function getMetadataFor(string $className)
	{
		return $this->entityMetadataFactory->getMetadataFor(ltrim($className, '\\'))
	}

	protected function getHydratorForClass($document)
	{
		return $this->getHydrator(get_class($document));
	}

	protected function getHydrator(string $className)
	{
		if($this->hydrators->contains($className)){
		  return $this->hydrators[$className];
		}

		$hydratorClass = str_replace('\\', '', ClassUtils::getClassRealname($className)) . 'Hydrator';
		$fn = $this->hydratorNamespace . '\\' . $hydratorClass;

		$classMetaData = $this->getMetadataFor($className);
		$fileName = $this->hydratorDir . DIRECTORY_SEPARATOR . $hydratorClass . '.php';

		if(!class_exists($fn, false)){ // Check if class exists but dont load it.
		  $this->generateHydratorClass($classMetaData,$hydratorClass, $fileName);
		}

		$this->hydrators[$className] = new $fn($this->entityMetadataFactory, $classMetaData);

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

      if($mapping->getIsInt()) {
            $code .= sprintf(<<<EOF
      if(array_key_exists('$name', \$data) && is_int(\$data['$name'])){
          \$r = \$data['$name'];
          
          \$this->class->reflFields['$name']->setValue(\$document, \$r);
          \$hydratedData['$name'] = \$data['$name'];
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

use Pi\Odm\Interfaces\IEntityMetaDataFactory,
	Pi\Odm\Interfaces\IHydrator,
	Pi\Odm\Interfaces\IEntity;



/**
 * ODM Hydrator class 
 * Generated by Pi Framework
 */
class $hydratorClassName implements IHydrator
{

    public function __construct(
    	protected IEntityMetaDataFactory \$entityMetadataFactory, protected EntityMetaData \$class
    )
    {
        
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
     

    // rename($tmpFileName, $fileName);
    if( copy($tmpFileName, $fileName) ) {
      unlink($tmpFileName);
    }
  }

}