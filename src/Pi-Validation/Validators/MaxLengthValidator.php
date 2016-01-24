<?hh
namespace Pi\Validation\Validators;

use Pi\Validation\PropertyValidatorContext;

/**
 * Validator to check if the property is null
 */
class MaxLengthValidator extends PropertyValidator{

	public function __construct(protected int $maxLength)
	{

	}

    public function isValid(PropertyValidatorContext $context) : bool
    {
    	$value = $context->getPropertyValue();

    	if($value === null) {
    		return false;
    	}

    	return is_string($value)
    		? strlen($value) <= $this->maxLength : $value <= $this->maxLength;
    }
}