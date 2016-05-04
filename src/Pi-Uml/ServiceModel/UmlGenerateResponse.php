<?hh

namespace Pi\Uml\ServiceModel;
use Pi\Response;

class UmlGenerateResponse extends Response {

	protected $raw;

	public function raw($value = null)
	{
		if($value === null) return $this->raw;
		$this->raw = $value;
	}
}
