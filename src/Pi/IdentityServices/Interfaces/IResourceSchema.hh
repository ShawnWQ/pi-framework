<?hh
namespace Pi\IdentitiServices\Interfaces;
interface IResourceSchema {

	/**
	 * @description
	 * The Resource name. 
	 * When applicable Service Providers MUST specify the name specified in the core schema specification; 
	 * e.g., "User" or "Group".
	 */
	public function getName();

	/**
	 * @description
	 * The Resource's human readable description. When applicable Service Providers MUST specify the description specified in the core schema specification.
	 */
	public function getDescription();

	/**
	 * @description
	 * The Resource's associated schema URI; e.g., urn:scim:schemas:core:1.0.
	 */
	public function getSchema();

	/**
	 * @description
	 * The Resource's HTTP addressable endpoint relative to the Base URL; e.g., /Users.
	 */
	public function getEndpoint();

	/**
	 * @description
	 * A complex type that specifies the set of Resource attributes.
	 */
	public function getAttributes();
}