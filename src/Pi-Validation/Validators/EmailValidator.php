<?hh
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 5/13/15
 * Time: 7:09 PM
 */

namespace Pi\Validation\Validators;


use Pi\Validation\PropertyValidatorContext;

/**
 * Validator for Emails
 */
class EmailValidator extends PropertyValidator{

    public function isValid(PropertyValidatorContext $context) : bool
    {
        return filter_var($context->getPropertyValue(), \FILTER_VALIDATE_EMAIL) !== false;
    }
}