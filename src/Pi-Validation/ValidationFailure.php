<?hh

namespace Pi\Validation;

class ValidationFailure {
	
	public function __construct(
		protected string $propertyName, 
		protected string $errorMessage, 
		protected ?string $attemptedValue = null)
	{

	}

	public function getPropertyName() : string
	{
		return $this->propertyName;
	}

	public function getErrorMessage() : string
	{
		return $this->errorMessage;
	}

	public function getAttemptedValue() : ?string
	{
		return $this->attemptedValue;
	}
}