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
			NotNullValidator::use(),
			MinLengthValidator::use(10),
			MaxLengthValidator::use(120)
			)
		);
		$this->ruleFor('excerpt')->setValidators(array(
			NotNullValidator::use(),
			MinLengthValidator::use(15),
			MaxLengthValidator::use(120)
			)
		);
		$this->ruleFor('title')->setValidators(array(
			NotNullValidator::use(),
			MinLengthValidator::use(20)
			)
		);
		$this->ruleFor('doorTime')->setValidator(NotNullValidator::use());
		$this->ruleFor('endDate')->setValidator(NotNullValidator::use());
	}
}