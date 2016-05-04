<?hh

namespace Pi\Validation;

class PropertyValidatorContext {

	protected bool $propertyValueSet = false;

	protected $propertyValue;

    protected $messageFormater;

	protected PropertyRule $propertyRule;


	public function __construct(
		protected ValidationContext $parentContext, 
		protected PropertyRule $rule, 
		protected string $propertyName)
	{

	}

    public function getPropertyName()
    {
        return $this->rule->getName();
    }



    public function getMessageFormater()
    {
        return $this->messageFormater;
    }

	public function instance()
	{
		return $this->parentContext->getObjectToValidate();
	}

	public function getPropertyValue()
	{
		if(!isset($this->propertyValueSet)) {
			$fn = $this->rule->getPropertyFn();
			$this->propertyValue = $fn($this->instance());
			$this->propertyValueSet = true;
		}

		return $this->propertyValue;
	}

	public function setPropertyValue($value)
	{
		$this->propertyValue = $value;
		$this->propertyValueSet = true;
	}

	public function getPropertyDescription() : string
	{
		return $this->rule->getDisplayName();
	}
}