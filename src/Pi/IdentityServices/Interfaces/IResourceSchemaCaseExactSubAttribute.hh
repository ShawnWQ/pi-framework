<?hh
namespace Pi\IdentitiServices\Interfaces;
interface IResourceSchemaCaseExactSubAttribute {

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
	 * @descriptionn
	 * The attribute's human readable description. When applicable Service Providers MUST specify the description specified in the core schema specification.
	 */
	public function getDescription();

	/**
	 * @description
	 * A Boolean value that specifies if the attribute is mutable.
	 */
	public function getReadOnly();

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
	 * A collection of canonical values. When applicable Service Providers MUST specify the canonical types specified in the core schema specification; e.g.,"work","home". OPTIONAL.
	 */
	public function getCanonicalValues();
}