<?hh

namespace Pi\Validation\Interfaces;

interface IValidationFactory {

	public function getValidator($instance) : IValidator;
}