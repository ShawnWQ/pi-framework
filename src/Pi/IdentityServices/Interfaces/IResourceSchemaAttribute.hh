<?hh
namespace Pi\IdentitiServices\Interfaces;
interface IResourceSchemaAttribute {

	/**
	 * @description
	 * The attribute's name.
	 */
	public function getName();

	/**
	 * @description
	 * The attribute's data type; e.g., String.
	 */
	public function getType();

	/**
	 * @description
	 * Boolean value indicating the attribute's plurality.
	 */
	public function getMultiValue();

	/**
	 * @description
	 * String value specifying the child XML element name; e.g., the 'emails' attribute value is 'email', 'phoneNumbers', is 'phoneNumber'. REQUIRED when the multiValued attribute value is true otherwise this attribute MUST be omitted.
	 */
	public function getMultiValuedAttributeChildName();

	/**
	 * @description
	 * The attribute's human readable description. When applicable Service Providers MUST specify the description specified in the core schema specification.
	 */
	public function getDescription();

	/**
	 * @description
	 * The attribute's associated schema; e.g., urn:scim:schemas:core:1.0.
	 */
	public function getSchema();

	/**
	 * @description
	 * A Boolean value that specifies if the attribute is mutable.
	 */
	public function getReadOnle();

	/**
	 * @description
	 * A Boolean value that specifies if the attribute is required.
	 */
	public function getRequired();

	/**
	 * @description
	 * A Boolean value that specifies if the String attribute is case sensitive.
	 */
	public function getCaseExact();

	/**
	 * @description
	 * A list specifying the contained attributes. OPTIONAL.
	 */
	public function getCaseExactSubAttributes();
}