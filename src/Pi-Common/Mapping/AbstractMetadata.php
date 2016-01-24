<?hh

namespace Pi\Common\Mapping;

abstract class AbstractMetadata {
	/**
	 * The Reflection Class
	 */
	public $reflClass;

	/**
	 * It's public to be accessed without getter/setter for performance reasons
	 * \ReflectionProperty[]
	 */
	public $reflFields;

	public $fields;

	public $id;

	/**
	 * The name of the document class
	 */
	public $name;

	/**
	 * The class namespace
	 */
	public $namespace;

	public $identifier;

	public function __construct(string $documentName)
	{
		$this->reflClass = new \ReflectionClass($documentName);
		$this->namespace = $this->reflClass->getNamespaceName();
		$this->name = $documentName;
	}


	public function getFieldValue(string $document, $field)
	{
		return $this->reflFields[$field]->getValue($document);
	}
	
	public function getReflectionClass()
	{
		if(!is_null($this->reflClass)){
			$this->reflClass = new \ReflectionClass($this->name);
		}

		return $this->reflClass;
	}

	public function getReflectionProperties()
	{
		return $this->fields;
	}

	public function getName()
	{
		return $this->name;
	}

	public function newInstance()
	{
		return new $this->name();
	}

	public function hasIdentifier()
	{
		return $this->identifier;
	}

	public function getIdentifierValue($document)
	{
			return isset($this->identifier) && isset($this->reflFields[$this->identifier])
							? $this->reflFields[$this->identifier]->getValue($document)
							: null;
	}
	public function setIdentifierValue(&$id, $document)
	{
		if(!isset($this->identifier)){
			throw new \Exception('The mapping of ' . get_class($document) . ' hasn\'t any Id mapped. Each document should have one Id');
		}
		$this->reflFields[$this->identifier]->setValue($document, $id);
	}
	public function getIdentifierValues($object)
	{
			return array($this->identifier => $this->getIdentifierValue($object));
	}

	public function setFieldValue($document, $field, $value)
	{
		$this->reflFields[$field]->setValue($document, $value);
	}
}
