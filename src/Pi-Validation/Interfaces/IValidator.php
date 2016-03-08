<?hh

namespace Pi\Validation\Interfaces;
use Pi\Validation\ValidationContext;
use Pi\Validation\ValidationResponse;

interface IValidator {
	
	public function validate($instance) : ValidationResponse;

	public function validateContext(ValidationContext $context);
}