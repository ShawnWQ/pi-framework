<?hh

namespace Pi\Validation;

class ValidationContext {

	public function __construct(protected $objectToValidate, protected PropertyChain $propertyChain = null)
    {
	    if($this->propertyChain === null) {
            $this->propertyChain = new PropertyChain();
        }
	}

	public function getObjectToValidate()
	{
		return $this->objectToValidate;
	}
}