<?hh

namespace Mocks;
use Pi\Validation\AbstractValidator;
use Pi\Validation\Validators\NotNullValidator;

/**
 * MockEntity validator
 */
class MockEntityValidator extends AbstractValidator {

	public function __construct()
	{
		parent::__construct(new MockEntity());
		$this->ruleFor('name')->setValidator(new NotNullValidator());
	}
}