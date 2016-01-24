<?hh

namespace Pi\Validation;
use Pi\Validation\Interfaces\IValidationRule;
use Pi\Validation\Interfaces\IValidator;
use Pi\Validation\Interfaces\IValidationProperty;
use Pi\Interfaces\IHasAppHost;
use Pi\Interfaces\IPiHost;

/**
 * Define validation rules for a custom DTO
 * The association between the request class type and the validator is done at the Container
 */
abstract class AbstractValidator implements IHasAppHost {

    /**
     * @var array
     */
    protected $nestedValidators = array();

    /**
     * @var PiValidationTypes
     */
    protected $type = PiValidationTypes::Inline;

    /**
     * The current AppHost
     */
    protected IPiHost $appHost;

    public function __construct($type = null)
    {
        if($type !== null) {
            $this->type = $type;
        }
    }

    /** 
     * @param $appHost IPiHost
     */
    public function setAppHost(IPiHost $appHost)
    {
        $this->appHost = $appHost;
    }

    /** 
     * @param $rule IValidationRule
     */
    public function addRule(IValidationRule $rule) : void
    {
        $this->nestedValidators[] = $rule;

    }

    /**
     * Defined the field affected by the validation rule
     * @param string $propertyName the class property name
     */
    public function ruleFor(string $propertyName)
    {
        $rule = PropertyRule::create($propertyName);
        $this->addRule($rule);
        $ruleBuilder = new RuleBuilder($rule);
        return $ruleBuilder;
    }

    public function createDescriptor()
    {
        return new ValidationDescriptor($this->nestedValidators);
    }

    /**
     * Validate the class
     */
    public function validate($instance) : ValidationResponse
    {
        // assert this validator can validate get_class of $instance
        $failures = array();
        return $this->validateContext(new ValidationContext($instance, new PropertyChain()));
    }

    /**
     * @param ValidationContext $ValidationContext
     */
    public function validateContext(ValidationContext $context)
    {
        $failures = Vector{};
        foreach($this->nestedValidators as $validator) {
            $r = $validator->validate($context);
            if(count($r) > 0) {
                $failures[] = $r;
            }

        }
        $response = new ValidationResponse(count($failures) > 0 ? $failures : null);

        return $response;
    }
}
