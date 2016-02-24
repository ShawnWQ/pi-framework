<?hh

namespace Pi\Odm\Mapping;

use Pi\Interfaces\DtoMappingMetadataInterface,
	Pi\Odm\Interfaces\IEntity,
	Pi\Odm\Mapping\EntityFieldMapping,
	Pi\Odm\CascadeOperation,
	Pi\Odm\MappingType,
	Pi\Common\Mapping\AbstractMetadata;

class EntityMetaData extends AbstractMetadata {

	/**
	 * The database name the document is mapped to
	 */
	public $database;

	/**
	 * The name of the mongo collection
	 */
	public $collection;

	/**
	 * The name of the field that's used to lock the document
	 */
	public $lockField;

	public $lifeCycleCallbacks;

	public $slaveOkay;


	public function __construct(string $documentName)
	{
		parent::__construct($documentName);
		$this->lifeCycleCallbacks = Map{};
		$this->collection = ($this->reflClass->getShortName());

	}

	/**
	 * Map a field to this metadata
	 * @param  DtoMappingMetadataInterface The metadata field mapping class
	 * @return void
	 */
	public function mapField(DtoMappingMetadataInterface $mapping) : void
	{
		// Most cases user will set only name of mapping, which is equal to fieldName
		if($mapping->getFieldName() === null && $mapping->getName() !== null){
			$mapping->setFieldName((string)$mapping->getName());
		} else if(is_null($mapping->getName()) && !is_null($mapping->getFieldName())){
			$mapping->setName((string)$mapping->getFieldName());
		}

		if($mapping instanceof EntityFieldMapping) {
			if($mapping->isCascade()) {
				switch($mapping->getCascade()) {
					case CascadeOperation::All:
					break;

					case CascadeOperation::Refresh:
					break;
				}
			}
		}
		
		if($this->reflClass->hasProperty($mapping->getFieldName())) {
			$reflProp = $this->reflClass->getProperty($mapping->getFieldName());
			$reflProp->setAccessible(true);
			$this->reflFields[$mapping->getName()] = $reflProp;
		}

		$this->fieldMappings[$mapping->getName()] = $mapping;
	}

	public function getDatabase()
	{
		return $this->database;
	}

	public function getDatabaseIdentifierValue($id = null)
	{
		if(!isset($this->identifier) || !isset($this->fieldMappings[$this->identifier])){
			return null;
		}
		$idType = $this->fieldMappings[$this->identifier]->getPHPType();
	  	//return Type::getType($idType)->convertToDatabaseValue($id);
	}

	public function hasLifeCycleCallbacks(string $event)
	{
		return $this->lifeCycleCallbacks->contains($event);
	}

	/**
	 * Dispatch the lifecycle event of the giving document
	 */
	public function invokeLifeCycleCallbacks(string $event, IEntity $document, ?array $arguments = null)
	{
		if(!$document instanceof $this->name){
			throw new \Exception('Expected document class %s, got %s', $this->name, get_class($document));
		}

		foreach($this->lifeCycleCallbacks[$event] as $callback){
			if(!is_null($arguments)){
				call_user_func_array(array($document, $callback), $arguments);
			} else {
				$document->$callback();
			}
		}
	}

	public function addLifeCycleCallback($callback, $event)
	{
		if($this->lifeCycleCallbacks->contains($event) && in_array($callback, $this->lifeCycleCallbacks[$event])){
			return; // Already added
		}

		$this->lifeCycleCallbacks[$event][] = $callback;
	}

	public function setLifeCycleCallbacks(array $callbacks)
	{
		$this->lifeCycleCallbacks = $callbacks;
	}


	public function getCollection()
	{
		return $this->collection;
	}

	public function setCollection(string $collection)
	{
		$this->collection = $collection;
	}
}
