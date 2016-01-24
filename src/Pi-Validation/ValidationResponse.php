<?hh

namespace Pi\Validation;

class ValidationResponse {

	public function __construct(protected ?Vector<ValidationFailure> $failures = null)
	{
        if($this->failures === null) {
            //$this->failures = Vector{};
        }

	}

	public function getFailures()
	{
		return $this->failures;
	}
	public function isValid() : bool
	{

		return $this->failures === null || count($this->failures) === 0;
	}
}