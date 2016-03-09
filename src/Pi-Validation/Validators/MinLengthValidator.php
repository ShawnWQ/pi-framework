<?hh
namespace Pi\Validation\Validators;

use Pi\Validation\PropertyValidatorContext;

/**
 * Validator to check if the property is null
 */
class MinLengthValidator extends PropertyValidator {

	public function __construct(protected int $minLength)
	{
        $this->errorMessage = sprintf(_('The property requires at least %s caracther%s'), (string)$minLength, $minLength == 1 ? '' : 's');
	}

    public static function instance(int $minLength)
    {
        return new self($minLength);
    }

    public function isValid(PropertyValidatorContext $context) : bool
    {
    	$value = $context->getPropertyValue();

    	if($value === null) {
    		return false;
    	}

    	return is_string($value)
    		? strlen($value) > $this->minLength : $value > $this->minLength;
    }
}