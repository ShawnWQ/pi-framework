<?hh

namespace Pi\Host;

use Pi\EventManager;
use Pi\Common\Mapping\AbstractMetadataFactory;
use Pi\Odm\Interfaces\IMappingDriver;
use Pi\Host\OperationDriver;
use Pi\Host\Operation;
use Pi\Odm\Interfaces\IEntityMetaDataFactory;

/**
 * Operation Metadata Factory
 *
 * Handle the registration of new Operation instances
 * Loads the metadata into classes
 */
class OperationMetaFactory extends AbstractMetadataFactory implements IEntityMetaDataFactory {

	private $loadedMetadata;

	private $initialized = false;

	public function __construct(
		protected EventManager $eventManager,
		protected OperationDriver $mappingDriver)
	{
		$this->loadedMetadata = Map{};
	}

	public function getClassMetadata($className)
	{
		
	}

	public function initialize()
	{

	}

	public function newEntityMetadataInstance(string $documentName)
	{
		return new Operation($documentName);
	}

	public function getRequestMetadata()
	{

	}

	public function doLoadMetadata(Operation $class)
	{
		try {
			$this->mappingDriver->loadMetadataForClass($class->getName(), $class);
		}
		catch(\Exception $ex){
			throw $ex;
		}

	}

	public function getMetadataFor(string $className)
	{
		if($this->loadedMetadata->contains($className)) {
			return $this->loadedMetadata->get($className);
		}

		return $this->loadMetadata($className);
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

	public function hasMetadataFor($className)
	{
    	return isset($this->loadedMetadata[$className]);
	}

	public function setMetadataFor($className, $class)
	{
		$this->loadedMetadata[$className] = $class;
	}


}
