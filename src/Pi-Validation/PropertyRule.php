<?hh

namespace Pi\Validation;
use Pi\Validation\Interfaces\IValidationRule;
use Pi\Validation\Interfaces\IValidationProperty;
use Pi\Validation\Interfaces\IValidator;

class PropertyRule implements IValidationRule {

	protected string $displayName;

	protected string $name;

	protected $onFailureCallback;

	protected $currentValidator;

	protected Vector<IValidationProperty> $validators = Vector{};

	public function __construct(
		protected $reflProperty, 
		protected $propertyFn = null, // function to get the property value
		protected $expression = null// Lambda expression used to create the rule
		) 
	{
        $this->setName($reflProperty->getName());
		// resolve $name and $displayName
	}

    public static function create($type, $property = null, $fieldName = null)
    {
        return new PropertyRule($type, $property, $fieldName);
    }

	public function getPropertyFn()
	{
		return $this->propertyFn;
	}

	public function setOnFailureCallback((function() : void) $closure)
	{
		$this->onFailureCallback = $closure;
	}

	public function getReflectionProperty()
	{
		return $this->reflProperty;
	}

	// Replaces a validator in this rule. Used to wrap validators. check if is current

	// Remove, check if is current

	public function addValidator(IValidationProperty $validator) : void
	{
		$this->currentValidator = $validator;
		$this->validators->add($validator);
	}

	public function clearValidators()
	{
		$this->validators->clear();
		$this->currentValidator = null;
	}

	public function getCurrentValidator() : IValidationProperty
	{
		return $this->currentValidator;
	}

	public function getValidators() : ?Vector<IValidationProperty>
	{
		return $this->validators;
	}

	public function getRuleSet() : ?string
	{
		return null;
	}

	public function setRuleSet(string $name) : void
	{

	}

    public function getName() : string
	{
        return $this->name;
    }

	public function setName(string $name) : void
	{
        $this->displayName = $name;
        $this->name = $name;
    }

	/**
	 * String source that can be used to retrieve the display name (if null, falls back to the property name)
	 */
	public function getDisplayName() : string
	{
		return $this->displayName;
	}

	public function setDisplayName(string $name) : void
	{
		$this->displayName = $name;
	}

	public function validate(ValidationContext $context) : ?Vector<ValidationFailure>
	{
		$name = $this->getDisplayName();
        $response = Vector{};
		$failed = false;

		foreach($this->validators as $validator) {
            $validation = $this->doValidateProperty($context, $validator, $name);

            if($validation instanceof ValidationFailure){
                $response->add($validation);
            }
		}

        return count($response) > 0 ? $response : null;
	}

	protected function doValidateProperty(ValidationContext $context, IValidationProperty $validator, string $propertyName)
	{

        $propertyContext = new PropertyValidatorContext($context, $this, $propertyName);
        $this->reflProperty->setAccessible(true);
        $val = $this->reflProperty->getValue($context->getObjectToValidate());
        $propertyContext->setPropertyValue($val, $propertyName);
        return $validator->validate($propertyContext);
	}
}