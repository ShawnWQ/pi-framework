<?hh
namespace Pi\IdentitiServices\Interfaces;
interface IUserEnterpriseAttributes {

	/**
	 + @description
	 * Numeric or alphanumeric identifier assigned to a person, typically based on order of hire or association with an organization.
	 */
	public function getEmployeeNumber();

	/**
	 * @description
	 * Identifies the name of a cost center.
	 */
	public function getCostCenter();

	/**
	 * @description
	 * Identifies the name of an organization.
	 */
	public function getOrganization();

	/**
	 * @description
	 * Identifies the name of a division.
	 */
	public function getDivision();

	/**
	 * @description
	 * Identifies the name of a department.
	 */
	public function getDepartment();
}