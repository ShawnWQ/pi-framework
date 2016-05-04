<?hh

namespace Pi\Validation;
use Pi\Validation\Interfaces\IValidationProperty;

class RuleBuilder {

	public function __construct(protected PropertyRule $rule)
	{

	}

	public function getRule()
	{
		return $this->rule;
	}

	/**
	 * Sets the validator associated with the rule.
	 */
	public function setValidator(IValidationProperty $validator)
	{
		$this->rule->addValidator($validator);
        return $this;
	}

	public function setValidators(array $validators)
	{
		foreach ($validators as $key => $value) {
			$this->rule->addValidator($value);
		}
	}
}