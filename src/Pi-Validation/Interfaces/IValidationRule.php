<?hh

namespace Pi\Validation\Interfaces;
use Pi\Validation\ValidationFailure;
use Pi\Validation\ValidationContext;

/**
 * A rule is defined with a property
 * Each rule may have many validators
 */
interface IValidationRule {

	public function getValidators() : ?Vector<IValidationProperty>;

	public function getRuleSet() : ?string;

	public function setRuleSet(string $name) : void;

	public function validate(ValidationContext $context) : ?Vector<ValidationFailure>;
}