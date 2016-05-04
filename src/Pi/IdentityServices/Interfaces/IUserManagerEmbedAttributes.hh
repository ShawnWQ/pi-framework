<?hh
namespace Pi\IdentitiServices\Interfaces;
/**
 * @description
 * The User's manager. 
 * A complex type that optionally allows Service Providers to represent organizational hierarchy by referencing the "id" attribute of another User.
 */
interface IUserManagerEmbedAttributes {
	/**
	 * @description
	 * The id of the SCIM resource representing the User's manager.
	 * @required
	 */
	public function getManagerId();

	/**
	 * @description
	 * The displayName of the User's manager.
	 * @optional
	 * @readonly
	 */
	public function getDisplayName();
}