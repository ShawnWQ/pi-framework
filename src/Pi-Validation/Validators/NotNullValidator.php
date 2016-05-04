<?hh
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 5/13/15
 * Time: 10:18 AM
 */

namespace Pi\Validation\Validators;

use Pi\Validation\PropertyValidatorContext;

/**
 * Validator to check if the property is null
 */
class NotNullValidator extends PropertyValidator{

    public function isValid(PropertyValidatorContext $context) : bool
    {
        return $context->getPropertyValue() !== null;
    }
}