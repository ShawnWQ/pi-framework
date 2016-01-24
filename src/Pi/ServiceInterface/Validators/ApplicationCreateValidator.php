<?hh

namespace Pi\ServiceInterface\Validators;
use Pi\Validation\AbstractValidator;
use Pi\Validation\Validators\NotNullValidator;

class ApplicationCreateValidator extends AbstractValidator {
    
    public function __construct()
    {
        $this->ruleFor('name')->setValidator(new NotNullValidator());
    }
}