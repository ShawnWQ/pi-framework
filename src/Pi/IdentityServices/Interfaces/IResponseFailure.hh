<?hh
namespace Pi\IdentitiServices\Interfaces;
interface IResponseFailure {
	public function getSchemas();

	public function getScimType(); // invalidSyntax

	public function getDetail();

	public function getStatus();
}