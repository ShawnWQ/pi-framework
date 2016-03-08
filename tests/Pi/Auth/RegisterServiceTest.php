<?hh
use Mocks\BibleHost,
	Mocks\MockHostProvider;
use Pi\ServiceModel\BasicRegisterRequest;
use Pi\ServiceModel\BasicRegisterResponse;
use Pi\ServiceModel\BasicAuthenticateRequest;
use Pi\ServiceModel\BasicAuthenticateResponse;
use Pi\Common\RandomString;
use Pi\Auth\RegisterService,
	Pi\Auth\CreateAccountConfirmationToken;
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
		$repo = $this->appHost->container()->get('Pi\Auth\UserRepository');
		$request = new BasicRegisterRequest();
		$request->firstName('Guilherme');
		$request->lastName('Cardoso');
		$request->displayName('Guilherme Cardoso');
		$request->email('email@guilhermecardoso.pt' . RandomString::generate(4));
		$request->password('123_123123');
		$request->passwordConfirm('123_123123');

		$response = MockHostProvider::execute($request);
		$this->assertTrue($response instanceof BasicRegisterResponse);

		// Assert Confirmation Token
		$id = $response->getId();
		$token = $this->appHost->container()->get('ICacheProvider')->get(RegisterService::REDIS_CONFIRM_TOKEN . (string)$id);
		$this->assertNotNull($token);

		$user = $repo->get($response->getId());
        $this->assertEquals($user->firstName(), $request->firstName());
        $this->assertEquals($user->state(), AccountState::EmailNotConfirmed);

        $response = MockHostProvider::execute($request);
        $this->assertTrue($response instanceof HttpResult);
        $this->assertTrue($response->response()['errorCode'] === AuthServiceError::EmailAlreadyRegistered);
	}
}
