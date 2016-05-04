<?hh
namespace Pi\IdentitiServices\Interfaces;
interface IUserMinimalInfo {

	public function getSchemas();

	public function getId();

	public function getUserName();
}