<?hh
namespace Mocks;
use Pi\Validation\AbstractValidator;
use Pi\Validation\Validators\NotNullValidator;

class VerseCreateValidator extends AbstractValidator {

	public function __construct()
	{
		parent::__construct(new VerseCreateRequest());
		$this->ruleFor('text')->setValidator(new NotNullValidator());
	}
}