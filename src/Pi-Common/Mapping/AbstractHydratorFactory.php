<?hh

namespace Pi\Common\Mapping;

use Pi\Extensions,
    Pi\Interfaces\HydratorFactoryInterface,
    Pi\Interfaces\DtoMetadataInterface,
    Pi\Interfaces\HydratorInterface,
    Pi\Odm\Interfaces\IEntityMetaDataFactory,
    Pi\Common\ClassUtils,
    Pi\Common\Mapping\HydratorAutoGenerate;




class AbstractHydratorFactory implements HydratorFactoryInterface {
	
	protected Map $hydrators;

  protected HydratorAutoGenerate $autoGenerate;
	
	public function __construct(
		protected IEntityMetaDataFactory $entityMetadataFactory,
		protected string $hydratorNamespace,
		protected string $hydratorDir,
    ?HydratorAutoGenerate $autoGenerate = null
		)
	{
    $this->autoGenerate = $autoGenerate ?:
      Extensions::testingMode() 
        ? HydratorAutoGenerate::Always
        : HydratorAutoGenerate::Always;

		if(empty($this->hydratorDir)){
	      throw new \Exception('The MongoHydratorFactory requires a valid $hydratorDir to save the hydrated files');
	    }

	    if(empty($this->hydratorNamespace)){
	      throw new \Exception('The $hydratorNamespace cant be empty, its required for autoloader');
	    }

	    $this->hydrators = Map{};
	}

  public function getInstanceOf(string $className, array $data)
  {
    $metadata = $this->getMetadataFor($className);
    $document = $metadata->newInstance();
    $hydrator = $this->getHydrator($className);
    $hydrator->hydrate($data, $document);
    return $document;
  }

  protected function removeHydratorFile(string $className) : void
  {
    $fileName = $this->getClassFileName($className);
    unlink($fileName);
  }

  protected function hydratorFileExists(string $className) : bool
  {
    $fileName = $this->getClassFileName($className);
    return file_exists($fileName);
  }

  public function hydrators() : Map
  {
    return $this->hydrators;
  }

  public function getHydratorClassName(string $className) : string
  {
    return str_replace('\\', '', ClassUtils::getClassRealname($className)) . 'Hydrator';
  }

  public function getFQCNHydrator(string $className) : string
  {
    return $this->hydratorNamespace . '\\' . $this->getHydratorClassName($className);
  }

  public function getClassFileName(string $className)  : string
  {
    $hydratorClass = $this->getHydratorClassName($className);
    $fn = $this->hydratorNamespace . '\\' . $hydratorClass;
    return $this->hydratorDir . DIRECTORY_SEPARATOR . $hydratorClass . '.php';
  }

  public function getMetadataFor(string $className)
  {
    return $this->entityMetadataFactory->getMetadataFor(ltrim($className, '\\'));
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

	public function getHydratorForClass($document) : HydratorInterface
	{
		return $this->getHydrator(get_class($document));
	}

	public function getHydrator(string $className) : HydratorInterface
	{
		if($this->hydrators->contains($className)){
		  return $this->hydrators[$className];
		}

		$hydratorClass = $this->getHydratorClassName($className);
    $fn = $this->getFQCNHydrator($className);
		$classMetaData = $this->getMetadataFor($className);
		$fileName = $this->getClassFileName($className);

		if(!class_exists($fn, false)){ // Check if class exists but dont load it.
      switch($this->autoGenerate) {
        case HydratorAutoGenerate::Never:
          require $fileName;

        case HydratorAutoGenerate::Always:
          $this->generateHydratorClass($classMetaData, $hydratorClass, $fileName);
          require $fileName;
          break;

        case HydratorAutoGenerate::FileNotExists:
          if(!file_exists($fileName)) {
            $this->generateHydratorClass($classMetaData, $hydratorClass, $fileName);  
            require $fileName;
          }
          break;
      }
		}

		$this->hydrators[$className] = new $fn($this->entityMetadataFactory, $classMetaData);
		return $this->hydrators[$className];
	}

  /**
   * Generates a Hydrator for a specific document and saves it
   */
  public function generateHydratorClass(DtoMetadataInterface $entity, string $hydratorClassName, string $fileName)
  {
    $hydratorNamespace = $this->hydratorNamespace;
    $code = '';
    $codeExtract = '';

    foreach($entity->mappings() as $key => $mapping){
      $fieldName = $mapping->getFieldName();
      $name = $mapping->getName();
      $codeExtract .= sprintf(<<<EOF
      \$field = \$this->class->reflFields['$name'];
      \$field->setAccessible(true);
      \$value = \$field->getValue(\$object);
      \n
EOF
        );

      if($mapping->getIsInt()) {
            $codeExtract .= sprintf(<<<EOF
      if(\$value != null && is_int(\$value)){
          \$r = \$data['$name'];
          \$hydratedData['$name'] = \$value;
       }\n
EOF
            );
            $code .= sprintf(<<<EOF
      if(array_key_exists('$name', \$data) && is_int(\$data['$name'])){
          \$r = \$data['$name'];
          \$this->class->reflFields['$name']->setValue(\$document, \$r);
          \$hydratedData['$name'] = \$data['$name'];
       }\n
EOF
            );
            
      } else if($mapping->isDateTime()) {
            $codeExtract .= sprintf(<<<EOF
        if(\$value != null) {
          try {
            \$hydratedData['$name'] = new \DateTime(\$value);  
          }
          catch(\Exception \$ex) {
            \$hydratedData['$name'] = \$value; 
          }
        }\n
EOF
        );
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
       }\n
EOF
          );
      } else {
            $codeExtract .= sprintf(<<<EOF
        if(\$value != null) {
          \$hydratedData['$name'] = \$value;
        }\n
EOF
        );
              $code .= sprintf(<<<EOF
       if (array_key_exists('$name', \$data)) {
          \$r = \$data['$name'];
          \$this->class->reflFields['$name']->setValue(\$document, \$r);
          \$hydratedData['$name'] = \$data['$name'];
       }\n
EOF
          );
      }


    }

    $code = sprintf(<<<EOF
<?hh

namespace $hydratorNamespace;

use Pi\Odm\Interfaces\IEntityMetaDataFactory,
	  Pi\Interfaces\HydratorInterface,
    Pi\Interfaces\DtoMetadataInterface;




/**
 * ODM Hydrator class 
 * Generated by Pi Framework
 */
class $hydratorClassName implements HydratorInterface
{

    public function __construct(
    	protected IEntityMetaDataFactory \$entityMetadataFactory, protected DtoMetadataInterface \$class
    )
    {
        
    }

    public function extract(\$object) 
    {
      \$hydratedData = array();
%s        return \$hydratedData;

    }


    public function hydrate(array \$data, \$document,)
    {
        \$hydratedData = array();
%s        return \$hydratedData;
    }
}
EOF
            ,
            $codeExtract,
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