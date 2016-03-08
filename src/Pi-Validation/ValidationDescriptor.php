<?hh
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 5/13/15
 * Time: 10:01 AM
 */

namespace Pi\Validation;
use Pi\Validation\Interfaces\IValidationDescriptor;

class ValidationDescriptor implements IValidationDescriptor {

    public function __construcT(protected $validationRules)
    {

    }

    public function getName() : string
    {
        return '';
    }

	public function getMethodsWithValidators() : Map<string, IValidationProperty>
    {
        return null;
    }

	public function getValidatorsForMethod(string $name) : Vector<IValidationProperty>
    {
return null;
    }

	public function getRulesForMethod(string $name) : Vector<IValidationRule>
    {
return null;
    }
}