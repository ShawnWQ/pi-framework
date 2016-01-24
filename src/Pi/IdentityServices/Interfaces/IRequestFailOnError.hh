<?hh
namespace Pi\IdentitiServices\Interfaces;
interface IRequestFailOnError {
	/**
	 * @description
	 * If true indicate the service provider should return on the first error
	 */
	public function getFailOnError();
}