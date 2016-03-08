<?hh

namespace Pi\Interfaces;

interface DtoMappingMetadataInterface {

	public function setArray();

	public function isArray();

	public function isNotNull() : bool;

	public function setIsNotNull() : void;

	public function setDateTime();

	public function isDateTime();

	public function setIsInt() : void;

	public function getIsInt() : bool;

	public function setString();

	public function isString();

	public function setInt();

	public function setPHPType($type);

	public function getPHPType();

	public function setTimestamp() : void;

	public function isTimestamp() : bool;

	public function getName() : ?string;

	public function setName(string $name) : void;

	public function setFieldName(string $fieldName) : void;

	public function getFieldName() : ?string;

	public function setVersion($version) : void;

	public function getVersion();

	public function setReferenceOne();

	public function getReferenceOne();

	public function setReferenceMany();

	public function isReferenceMany();

	public function setEmbedOne();

	public function setEmbedType($type);

	public function getEmbedType();

	public function isEmbedOne();

	public function setEmbedMany($type);

	public function isEmbedMany();
  
	public function getDefaultDiscriminatorValue() : ?string;

	public function setDefaultDiscriminatorValue(string $value) : void;

	public function getInheritanceType() : ?string;

	public function setInheritanceType(string $value) : void;

	public function getDiscriminatorField() : ?string;

	public function setDiscriminatorField(string $value) : void;
}