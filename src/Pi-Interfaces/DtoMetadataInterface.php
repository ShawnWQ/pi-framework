<?hh

namespace Pi\Interfaces;

interface DtoMetadataInterface {

	public function getName() : string;
	
	public function getFieldValue(string $document, $field) : mixed;

	public function getReflectionClass() : \ReflectionClass;

	public function getReflectionProperties() : array;

	public function newInstance();

	public function mapField(DtoMappingMetadataInterface $mapping) : void;

	public function mappings() : array;

	public function hasIdentifier() : bool;

	public function getIdentifierValue($document);

	public function setFieldValue($document, $field, $value);

	public function hasField(string $fieldName) : bool;

	public function setId(string $name);

	public function getId();

	public function getIdentifierObject($document);

	public function getPHPIdentifierValue($id = null);

	public function setIdentifierValue(&$id, $document);

	public function setMappedSuperclass(bool $value = true) : void;

	public function isMappedSuperclass() : bool;

	public function getDiscriminatorField() : ?string;

	public function getDefaultDiscriminatorValue() : ?string;

	public function setDiscriminator(string $field, ?string $inheritanceType = 'Single', ?string $defaultValue = null);

	public function getInheritanceType() : ?string;

	public function setInheritanceType(string $value) : void;

	public function setMultiTenant(bool $enabled);

	public function setMultiTenantField(string $fieldName) : void;

	public function getMultiTenantField();

	public function getMultiTenantMode();

	public function isFile();

	public function isReference();

	public function setEmbeddedDocument();
	
	public function isEmbeddedDocument();
}