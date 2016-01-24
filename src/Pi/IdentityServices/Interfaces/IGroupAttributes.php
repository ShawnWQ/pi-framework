<?hh
namespace Pi\IdentitiServices\Interfaces;
interface IGroupAttributes {

	/**
	 * @description
	 * A human readable name for the Group.
	 * @required
	 */
	public function getDisplayName();

	/**
	 * @description
	 * A list of members of the Group. Canonical Types "User" and "Group" are READ-ONLY. The value must be the "id" of a SCIM resource, either a User, or a Group. The intention of the Group type is to allow the Service Provider to support nested Groups. Service Providers MAY require Consumers to provide a non-empty members value based on the "required" sub attribute of the "members" attribute in Group Resource Schema.
	 */
	public function getMembers();
}