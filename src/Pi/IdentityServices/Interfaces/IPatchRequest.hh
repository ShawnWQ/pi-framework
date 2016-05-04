<?hh
namespace Pi\IdentityServices\Interfaces;

interface IPatchRequest {

	public function getSchemas();

	/**
	 * @description
	 * an array of one or more patch operations.
	 */
	public function getOperations();
}