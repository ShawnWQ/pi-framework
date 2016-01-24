<?hh

 
use Mocks\MockEntity;

class AbstractValidatorTest extends \PHPUnit_Framework_TestCase{

    protected $validator;

    public function setUp()
    {
        $this->validator = new \Pi\Validation\InlineValidator();
    }

    public function testWhenValidatorsPassTheRunnerShouldReturnTrue()
    {
        $this->validator->ruleFor('name')->setValidator(new \Pi\Validation\Validators\NotNullValidator());
        $e = new MockEntity();
        $e->name('default-name-is-valid');
        $this->assertTrue($this->validator->validate($e)->isValid());
    }

    public function testWhenValidatorsFailTheRunnerShouldReturnFalse()
    {
        $this->validator->ruleFor('name')->setValidator(new \Pi\Validation\Validators\NotNullValidator());
        $e = new MockEntity();
        $this->assertFalse($this->validator->validate($e)->isValid());
    }

    public function testWhenValidatorsFailsErrorsShouldBeAccessibleFromErrorsProperty()
    {
        $this->validator->ruleFor('name')->setValidator(new \Pi\Validation\Validators\NotNullValidator());
        $e = new MockEntity();
        $res = $this->validator->validate($e);
        $this->assertFalse($res->isValid());

    }

    public function testShouldValidatePublicField()
    {

    }

    public function testWithMessageShouldOverrideDefaultMessage()
    {

    }

    public function testWithNameShouldOverrideDefaultFieldName()
    {

    }

    public function testShouldThrowWhenRuleIsNull()
    {

    }

    public function testShouldValidateSingleProperty()
    {

    }
}