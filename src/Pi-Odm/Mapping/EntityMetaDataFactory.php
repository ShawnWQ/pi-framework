<?hh

namespace Pi\Odm\Mapping;
use Pi\EventManager;
use Pi\Odm\Events;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;
use Pi\Odm\Interfaces\IMappingDriver;
use Pi\Common\Mapping\AbstractMetadataFactory;
use Pi\Odm\Interfaces\IEntityMetaDataFactory;
/**
 * The ClassMetadataFactory is used to create ClassMetadata objects that contain all the
 * metadata mapping informations of a class which describes how a class should be mapped
 * to a document database.
 */
class EntityMetaDataFactory extends AbstractMetadataFactory implements IContainable, IEntityMetaDataFactory {

	private $documentManager;

	private $entityMeta;

	private $loadedMetadata;

	private $initialized = false;

	public function __construct(
		protected EventManager $eventManager,
		protected IMappingDriver $mappingDriver)
	{
		$this->loadedMetadata = Map{};
	}

	public function ioc(IContainer $container)
	{

		//$this->eventManager = $container->get('EventManager');
		//$this->mappingDriver = $container->get('IMappingDriver');
	}

	public function initialize()
	{

	}


  public function newEntityMetadataInstance(string $documentName)
  {
    return new EntityMetaData($documentName);
  }

	protected function loadMetadata(string $name)
  {
    if ( ! $this->initialized) {
        $this->initialize();
    }

    $loaded = array();

    $visited = array();

		$className = $name;

    $class = $this->newEntityMetadataInstance($className);


    $this->doLoadMetadata($class);
		$this->setMetadataFor($className, $class);

    $loaded[] = $className;


    return $class;
  }
/**
   * Gets the class metadata descriptor for a class.
   *
   * @param string $className The name of the class.
   *
   * @return ClassMetadata
   *
   * @throws ReflectionException
   * @throws MappingException
   */
  public function getMetadataFor(string $className)
  {
		if($this->loadedMetadata->contains($className)){
			return $this->loadedMetadata->get($className);
		}

		$meta = $this->loadMetadata($className);
		return $meta;
  }
	protected function getFqcnFromAlias($namespaceAlias, $simpleClassName)
    {
        //return $this->config->getDocumentNamespace($namespaceAlias) . '\\' . $simpleClassName;
    }
  /**
   * Checks whether the factory has the metadata for a class loaded already.
   *
   * @param string $className
   *
   * @return boolean TRUE if the metadata of the class in question is already loaded, FALSE otherwise.
   */
  public function hasMetadataFor($className)
  {
      return isset($this->loadedMetadata[$className]);
  }

  /**
   * Sets the metadata descriptor for a specific class.
   *
   * NOTE: This is only useful in very special cases, like when generating proxy classes.
   *
   * @param string        $className
   * @param ClassMetadata $class
   *
   * @return void
   */
  public function setMetadataFor($className, $class)
  {
      $this->loadedMetadata[$className] = $class;
  }

	public function doLoadMetadata(EntityMetaData $class)
	{
		try {
			$this->mappingDriver->loadMetadataForClass($class->getName(), $class);
		}
		catch(\Exception $ex){
			throw $ex;
		}
		if($this->eventManager->has(Events::LoadClassMetadata)){
			$args = new LoadClassMetadataEventArgs($class, $this->documentManager);
			$this->eventManager->dispatch(Events::LoadClassMetadata, $args);
		}
	}
}
