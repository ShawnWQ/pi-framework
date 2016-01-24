<?hh

namespace Pi\Common\Mapping;
use Pi\Common\Mapping\MappingType;

abstract class AbstractFieldMapping {

	protected $fieldName;

	protected $version;

	protected $name;

	/**
	* The PHP Type
	* @var [type]
	*/
	protected $type;


	public function setString()
	{
		$this->type = MappingType::String;
	}
	public function isString()
	{
		return $this->type === MappingType::String;
	}

	public function setInt()
	{
		$this->type = MappingType::Int;
	}

	public function setPHPType($type)
	{
		$this->type = $type;
	}

	public function getPHPType()
	{
		return $this->type;
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
