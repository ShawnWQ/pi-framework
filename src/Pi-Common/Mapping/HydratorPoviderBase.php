<?hh

namespace Pi\Common\Mapping;


use Pi\Extensions,
    Pi\Interfaces\HydratorFactoryInterface,
    Pi\Interfaces\DtoMetadataInterface,
    Pi\Interfaces\HydratorInterface,
    Pi\Odm\Interfaces\IEntityMetaDataFactory,
    Pi\Common\ClassUtils,
    Pi\Common\Mapping\HydratorAutoGenerate;




trait HydratorPoviderBase {
	
	protected Map<string,string> $hydrators;
	protected $hydratorDir;

	protected $hydratorNamespace;
	public function __construct(
    	string $hydratorPath,
    	string $hydratorNamespace,
    	?HydratorAutoGenerate $autoGenerate = null)
	{
		if(empty($hydratorPath) || empty($hydratorNamespace)) {
    		throw new \Exception('The Hydrator path and namespace cant be empty');
	    }
	    $this->hydratorDir = $hydratorPath;
	    $this->hydratorNamespace = $hydratorNamespace;

	    $this->autoGenerate = $autoGenerate ?: 
	      Extensions::testingMode() 
	      ? HydratorAutoGenerate::Always
	      : HydratorAutoGenerate::Always;
	    $this->hydrators = Map{};
	}

	abstract function getMetadataFor(string $className) : array;

    abstract function generateHydratorClass(array $map, string $hydratorClassName, string $fileName);

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

    public function getHydratorForClass($document) : HydratorInterface
    {
      return $this->getHydrator(get_class($document));
    }

    public function getHydrator(string $className) 
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

      return $this->hydrators[$className] = new $fn($this);;
    }
}
