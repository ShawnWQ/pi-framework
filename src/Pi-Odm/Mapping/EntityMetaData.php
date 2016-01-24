<?hh

namespace Pi\Odm\Mapping;

use Pi\Odm\Interfaces\IEntity;
use Pi\Odm\Mapping\EntityFieldMapping;
use Pi\Odm\CascadeOperation;
use Pi\Odm\MappingType;
use Pi\Common\Mapping\AbstractMetadata;

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
	 * Array of indexes for the document collection
	 */
	public $indexes = Set{};

	/**
	 * Whether this class describes the mapping of a embedded document.
	 */
	public $isEmbeddedDocument = false;

	public $embeddedDocument;

	public $reference = false;

	/**
	 * The name of the field that's used to lock the document
	 */
	public $lockField;

	public $fieldsMapping;

	public $lifeCycleCallbacks;

	public $slaveOkay;

	protected $fieldMappings;

	protected $isFile = false;

	protected $multiTenantEnabled = false;

	protected $multiTenantField;

	protected string $defaultDiscriminatorValue;

	protected string $discriminatorField;

  protected string $inheritanceType;

	protected $isSuperclass = false;


	public function __construct(string $documentName)
	{
		parent::__construct($documentName);
		$this->lifeCycleCallbacks = Map{};
		$this->collection = ($this->reflClass->getShortName());

	}

	public function setMappedSuperclass(bool $value = true) : void
	{
		$this->isSuperclass = $value;
	}

	public function isMappedSuperclass() : bool
	{
		return $this->isSuperclass;
	}

	public function getDiscriminatorField() : ?string
	{
		return $this->discriminatorField;
	}

	public function getDefaultDiscriminatorValue() : ?string
	{
		return $this->discriminatorField;
	}

	public function setDiscriminator(string $field, ?string $inheritanceType = 'Single', ?string $defaultValue = null)
	{
		$this->discriminatorField = $field;
		$this->inheritanceType = $inheritanceType;
		if(!is_null($defaultValue)) {
			$this->defaultDiscriminatorValue = $defaultValue;
		}
	}

	public function getInheritanceType() : ?string
	{
		return $this->inheritanceType;
	}

	public function setInheritanceType(string $value) : void
	{
		$this->inheritanceType = $value;
	}

	public function setMultiTenant(bool $enabled)
	{
		$this->multiTenantEnabled = $enabled;
	}

	public function setMultiTenantField(string $fieldName) : void
	{
		$this->multiTenantField = $fieldName;
	}

	public function getMultiTenantField()
	{
		return $this->multiTenantField;
	}

	public function getMultiTenantMode()
	{
		return $this->multiTenantEnabled;
	}

	public function isFile()
	{
		return $this->isFile;
	}

	public function isReference()
	{
		return isset($this->reference);
	}

	public function setEmbeddedDocument()
	{
		$this->isEmbeddedDocument = true;
	}

	public function isEmbeddedDocument()
	{
		return $this->isEmbeddedDocument;
	}

	public function setIdentifierValue(&$id, $document)
	{
		if(!isset($this->identifier) && !$this->isEmbeddedDocument){
			throw new \Exception('The mapping of ' . get_class($document) . ' hasn\'t any Id mapped. Each document should have one Id');
		}
		$this->reflFields[$this->identifier]->setValue($document, $id);
	}

	public function getDatabase()
	{
		return $this->database;
	}

	public function getIdentifierObject($document)
	{
		return $this->getDatabaseIdentifierValue($this->getIdentifierValue($document));
	}


	public function getPHPIdentifierValue($id = null)
	{
		return $this->getDatabaseIdentifierValue($id);
	}

	public function getDatabaseIdentifierValue($id = null)
	  {
			if(!isset($this->identifier) || !isset($this->fieldMappings[$this->identifier])){
				return null;
			}
	      $idType = $this->fieldMappings[$this->identifier]->getPHPType();
	      //return Type::getType($idType)->convertToDatabaseValue($id);
	  }
	public function mappings()
	{
		return $this->fieldMappings;
	}

	public function mapField(EntityFieldMapping $mapping)
	{
		// Most cases user will set only name of mapping, which is equal to fieldName
		if($mapping->getFieldName() === null && $mapping->getName() !== null){
			$mapping->setFieldName((string)$mapping->getName());
		} else if(is_null($mapping->getName()) && !is_null($mapping->getFieldName())){
			$mapping->setName((string)$mapping->getFieldName());
		}


		if($mapping->isCascade()) {
			switch($mapping->getCascade()) {
				case CascadeOperation::All:
				break;

				case CascadeOperation::Refresh:
				break;
			}
		}

		if($this->reflClass->hasProperty($mapping->getFieldName())) {
			$reflProp = $this->reflClass->getProperty($mapping->getFieldName());
			$reflProp->setAccessible(true);
			$this->reflFields[$mapping->getName()] = $reflProp;
		}

		$this->fieldMappings[$mapping->getName()] = $mapping;
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

	public function hasField(string $fieldName) : bool
	{
		return $this->fieldsMapping->contains($fieldName);
	}

	public function setId(string $name)
	{
		$this->id = $name;
		$this->identifier = $name;
	}

	public function getId()
	{
		return $this->identifier;
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
