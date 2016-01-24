<?hh
use Mocks\BibleHost;
use Pi\ServiceModel\BasicRegisterRequest;
use Pi\ServiceModel\BasicRegisterResponse;
use Pi\ServiceModel\BasicAuthenticateRequest;
use Pi\ServiceModel\BasicAuthenticateResponse;
use Pi\Common\RandomString;
use Pi\Auth\RegisterService;
use Pi\Auth\UserEntity;
use Pi\Auth\AuthServiceError;
use Pi\HttpResult;
use Pi\Auth\AccountState;

class RegisterServiceTest extends \PHPUnit_Framework_TestCase {

	protected $appHost;

	public function setUp()
	{
		$this->appHost = new BibleHost();
		$this->appHost->init();
	}
	public function testRegisterNewAccountWithDefaultConfigState()
	{
		$request = new BasicRegisterRequest();
		$request->firstName('Guilherme');
		$request->lastName('Cardoso');
		$request->displayName('Guilherme Cardoso');
		$request->email('email@guilhermecardoso.pt' . RandomString::generate(4));
		$request->password('123_123123');

		$service = $this->appHost->container->getService(new RegisterService());
		$response = $service->basicRegistration($request);

		$this->assertTrue($response instanceof BasicRegisterResponse);

		$repo = $this->appHost->container()->get('Pi\Auth\UserRepository');

		$user = $repo->get($response->getId());
        $this->assertEquals($user->firstName(), $request->firstName());
        $this->assertEquals($user->state(), AccountState::EmailNotConfirmed);

        $response = $service->basicRegistration($request);
        $this->assertTrue($response instanceof HttpResult);
        $this->assertTrue($response->response()['errorCode'] === AuthServiceError::EmailAlreadyRegistered);
	}


}
