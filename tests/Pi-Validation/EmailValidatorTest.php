<?hh
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 5/13/15
 * Time: 7:09 PM
 */
use Mocks\MockEntity;

class RequestValidator extends \Pi\Validation\AbstractValidator {

    public function __construct()
    {
        $this->ruleFor('name')->setValidator(new \Pi\Validation\Validators\EmailValidator());
    }
}
class EmailValidatorTest extends \PHPUnit_Framework_TestCase {

    protected $validator;

    public function setUp()
    {
        $this->validator = new \Pi\Validation\InlineValidator();
    }

    public function testRequestValidator()
    {
        $validator = new RequestValidator();
        $entity = new MockEntity();
        $entity->name('email@guilhermecardoso.com');
        $this->assertTrue($validator->validate($entity)->isValid());

        $entity = new MockEntity();
        $this->assertFalse($validator->validate($entity)->isValid());
    }

    public function testRegexCanValidateIncorrectEmail()
    {
        $this->validator->ruleFor('name')->setValidator(new \Pi\Validation\Validators\EmailValidator());
        $e = new MockEntity();
        $e->name('emailguilhermecardoso.pt');
        $this->assertFalse($this->validator->validate($e)->isValid());
    }

    public function testRegexCanValidateCorrectEmail()
    {
        $this->validator->ruleFor('name')->setValidator(new \Pi\Validation\Validators\EmailValidator());
        $e = new MockEntity();
        $e->name('email@guilhermecardoso.pt');
        $this->assertTrue($this->validator->validate($e)->isValid());
    }
}