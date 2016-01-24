<?hh

namespace Pi\Validation\Interfaces;

interface IValidationDescriptor {

	public function getName() : string;

	public function getMethodsWithValidators() : Map<string, IValidationProperty>;

	public function getValidatorsForMethod(string $name) : Vector<IValidationProperty>;

	public function getRulesForMethod(string $name) : Vector<IValidationRule>;
}