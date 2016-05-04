<?hh

namespace Pi\Auth\Validators;

use Pi\Validation\AbstractValidator,
	Pi\Validation\Validators\NotNullValidator,
	Pi\Validation\Validators\MinLengthValidator,
	Pi\Validation\Validators\MaxLengthValidator,
	Pi\Validation\Validators\EmailValidator;

class BasicRegistrationValidator extends AbstractValidator {

	public function __construct()
	{
		parent::__construct('Pi\ServiceModel\BasicRegisterRequest');
		$this->ruleFor('firstName')->setValidators(array(
			NotNullValidator::instance(),
			MinLengthValidator::instance(3),
			MaxLengthValidator::instance(120)
			)
		);
		$this->ruleFor('lastName')->setValidators(array(
			NotNullValidator::instance(),
			MinLengthValidator::instance(3),
			MaxLengthValidator::instance(120)
			)
		);
		$this->ruleFor('email')->setValidator(EmailValidator::instance());
		$this->ruleFor('password')->setValidators(array(
			MinLengthValidator::instance(6),
			MaxLengthValidator::instance(120)
			)
		);
		$this->ruleFor('passwordConfirm')->setValidators(array(
			MinLengthValidator::instance(6),
			MaxLengthValidator::instance(120)
			)
		);
	}
}