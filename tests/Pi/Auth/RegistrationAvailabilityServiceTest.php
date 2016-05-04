<?hh
use Mocks\BibleHost;
use Pi\ServiceModel\BasicRegisterRequest;
use Pi\ServiceModel\BasicRegisterResponse;
use Pi\Common\RandomString;
use Pi\Auth\RegisterService;
use Pi\Auth\UserEntity;
use Pi\Auth\AccountState;
use Pi\Auth\RegistrationAvailabilityService;

class RegistrationAvailabilityrServiceTest extends \PHPUnit_Framework_TestCase
{

    protected $appHost;

    protected $unitWork;

    public function setUp()
    {
        $this->appHost = new BibleHost();
        $this->appHost->init();
        $this->unitWork = $this->appHost->container()->get('UnitWork');
    }

    public function testFalseForAlreadyRegisteredEmail()
    {
        $email = RandomString::generate(40) . '@msn.com';
        $entity = new UserEntity();
        $entity->email($email);
        $this->unitWork->persist($entity);
        $this->unitWork->commit();

        $service = $this->getService();
        $this->assertTrue($service instanceof RegistrationAvailabilityService);
        $request = new \Pi\ServiceModel\RegistrationAvailabilityRequest();
        $request->setEmail($entity->email());
        $response = $service->verifyEmail($request);
        $this->assertFalse($response->isAvailable());
    }

    public function testTrueForNonRegisteredEmail()
    {
        $request = new \Pi\ServiceModel\RegistrationAvailabilityRequest();
        $request->setEmail(RandomString::generate(50) . '@msn.com');
        $service = $this->getService();
        $res = $service->verifyEmail($request);
        $this->assertTrue($res->isAvailable());
    }

    private function getService()
    {
        return $this->appHost->container()->getService(new RegistrationAvailabilityService());
    }
}