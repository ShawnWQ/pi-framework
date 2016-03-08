<?hh

namespace Pi\Validation;

class ValidationException extends \Exception {
	
	public function __construct(protected $result)
	{

	}

	public function getResult()
	{
		return $this->result;
	}
}