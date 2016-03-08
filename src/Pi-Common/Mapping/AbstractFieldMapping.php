<?hh

namespace Pi\Common\Mapping;

use Pi\Odm\MappingType,
	Pi\Interfaces\DtoMappingMetadataInterface;

abstract class AbstractFieldMapping implements DtoMappingMetadataInterface {

	protected $fieldName;

	protected $version;

	protected $name;

	/**
	* The PHP Type
	* @var [type]
	*/
	protected $type;

	protected $opts;

	protected $embeded;

	protected $embedOne = false;

	protected $embedMany = false;

	protected $embedType;

	protected $referenceOne = false;

	protected $referenceMany = false;

	protected ?string $defaultDiscriminatorValue;

	protected ?string $inheritanceType;

	protected ?string $discriminatorField;

	public function __construct()
	{
		$this->opts = Set{};
	}

	public function setReferenceOne()
	{
	$this->referenceOne = true;
	}

	public function getReferenceOne()
	{
	return $this->referenceOne;
	}

	public function setReferenceMany()
	{
	$this->referenceMany = true;
	}

	public function isReferenceMany()
	{
	return $this->referenceMany;
	}

	public function setEmbedOne()
	{
	$this->embedOne = true;
	}

	public function setEmbedType($type)
	{
	$this->embedType = $type;
	}

	public function getEmbedType()
	{
	return $this->embedType;
	}

	public function isEmbedOne()
	{
	return $this->embedOne;
	}

	public function setEmbedMany($type)
	{
	$this->embedMany = true;
	$this->embedType = $type;
	}

	public function isEmbedMany()
	{
	return $this->embedMany;
	}

	public function getDefaultDiscriminatorValue() : ?string
	{
		return $this->defaultDiscriminatorValue;
	}

	public function setDefaultDiscriminatorValue(string $value) : void
	{
		$this->difaultDescriminatorValue = $value;
	}

	public function getInheritanceType() : ?string
	{
		return $this->inheritanceType;
	}

	public function setInheritanceType(string $value) : void
	{
		$this->inheritanceType = $value;
	}

	public function getDiscriminatorField() : ?string
	{
		return $this->discriminatorField;
	}

	public function setDiscriminatorField(string $value) : void
	{
		$this->discriminatorField = $value;
	}

	public function setArray()
	{
		$this->type = MappingType::Collection;
	}

	public function isArray()
	{
		return $this->type == MappingType::Collection;
	}

	public function isNotNull() : bool
	{
		return $this->opts->contains('notNull');
	}

	public function setIsNotNull() : void
	{
		$this->opts->add('notNull');
	}

	public function setDateTime()
	{
		$this->type = MappingType::Date;
	}

	public function isDateTime()
	{
		return $this->type == MappingType::Date;
	}

	public function setIsInt() : void
	{
		$this->type = MappingType::Int;
	}

	public function getIsInt() : bool
	{
		return $this->type == MappingType::Int;
	}

	public function setString()
	{
		$this->type = MappingType::String;
	}
	public function isString()
	{
		return $this->type == MappingType::String;
	}

	public function setInt()
	{
		$this->type = MappingType::Int;
	}

	public function isInt()
	{
		return $this->type == MappingType::Int;
	}

	public function setPHPType($type)
	{
		$this->type = $type;
	}

	public function getPHPType()
	{
		return $this->type;
	}

	public function setTimestamp() : void
	{
		$this->type = MappingType::Timestamp;
	}

	public function isTimestamp() : bool
	{
		return $this->type == MappingType::Timestamp;
	}

	public function getName() : ?string
	{
	return $this->name;
	}

	public function setName(string $name) : void
	{
		$this->name = $name;
	}

	public function setFieldName(string $fieldName) : void
	{
	  $this->fieldName = $fieldName;
	}

	public function getFieldName() : ?string
	{
		return $this->fieldName;
	}

	public function setVersion($version) : void
	{
		$this->version = $version;
	}

	public function getVersion()
	{
		return $this->version;
	}
}