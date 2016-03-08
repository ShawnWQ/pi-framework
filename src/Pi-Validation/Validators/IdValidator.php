<?hh

namespace Pi\Validation\Validators;
use Pi\Validation\Interfaces\IValidator;
use Pi\Validation\Interfaces\IValidationProperty;
use Pi\Validation\PropertyValidatorContext;
use Pi\Validation\ValidationContext;
use Pi\Validation\ValidationFailure;
class IdValidator extends PropertyValidator {
	
	public function isValid(PropertyValidatorContext $context) : bool
    {
        return \MongoId::isValid($context->getPropertyValue());
    }

}