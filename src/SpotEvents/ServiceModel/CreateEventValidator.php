<?hh

namespace SpotEvents\ServiceModel;

use Pi\Validation\AbstractValidator,
	Pi\Validation\Validators\NotNullValidator,
	Pi\Validation\Validators\MinLengthValidator,
	Pi\Validation\Validators\MaxLengthValidator;




class CreateEventValidator extends AbstractValidator {
	
	public function __construct()
	{
		parent::__construct('SpotEvents\ServiceModel\CreateEvent');
		$this->ruleFor('title')->setValidators(array(
			NotNullValidator::instance(),
			MinLengthValidator::instance(10),
			MaxLengthValidator::instance(120)
			)
		);
		$this->ruleFor('excerpt')->setValidators(array(
			NotNullValidator::instance(),
			MinLengthValidator::instance(15),
			MaxLengthValidator::instance(120)
			)
		);
		$this->ruleFor('title')->setValidators(array(
			NotNullValidator::instance(),
			MinLengthValidator::instance(20)
			)
		);
		$this->ruleFor('doorTime')->setValidator(NotNullValidator::instance());
		$this->ruleFor('endDate')->setValidator(NotNullValidator::instance());
	}
}