<?php
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 5/13/15
 * Time: 10:13 AM
 */
namespace Pi\Validation\Validators;
use Pi\Validation\Interfaces\IValidator;
use Pi\Validation\Interfaces\IValidationProperty;
use Pi\Validation\PropertyValidatorContext;
use Pi\Validation\ValidationContext;
use Pi\Validation\ValidationFailure;

abstract class PropertyValidator implements IValidationProperty{

    protected string $errorMessage = 'Invalid input.';
    public static function instance()
    {
        return new static;
    }
    
    public function validate(PropertyValidatorContext $context)
    {
        if(!$this->isValid($context)) {

            $error = $this->createValidationError($context);
            return $error;
        }
        return true;
    }

    protected function createValidationError(PropertyValidatorContext $context)
    {
        $failure = new ValidationFailure($context->getPropertyName(), $this->errorMessage, $context->getPropertyValue());
        return $failure;
    }

    public abstract function isValid(PropertyValidatorContext $context);
}