<?hh

use Mocks\BibleHost,
    Mocks\MockHostProvider,
    Pi\HttpResult,
    Pi\SessionFactory,
    Pi\Interfaces\IService,
    Pi\ServiceModel\AuthAuthorize,
    Pi\ServiceModel\AuthToken,
    Pi\ServiceModel\BasicRegisterRequest,
    Pi\ServiceModel\BasicRegisterResponse,
    Pi\ServiceModel\BasicAuthenticateRequest,
    Pi\ServiceModel\BasicAuthenticateResponse,
    Pi\Auth\AuthService,
    Pi\Auth\Interfaces\IAuthSession,
    Pi\Auth\Interfaces\IAuthTokens,
    Pi\Auth\Authenticate,
    Pi\Auth\AuthProvider,
    Pi\Common\RandomString,
    Pi\Auth\RegisterService,
    Mocks\HttpRequestMock,
    Mocks\MockCryptorProvider,
    Mocks\MockHostConfiguration;

class MockOAuthProvider extends AuthProvider{

  public function authenticate(IService $authService, IAuthSession $session, Authenticate $request)
  {
    return null;
  }

  public function isAuthorized(IAuthSession $session, IAuthTokens $tokens, Authenticate $request = null)
  {
    return true;
  }

}
class AuthProviderTest extends \PHPUnit_Framework_TestCase {

  protected $provider;

  protected $authSvc;

  public function setUp()
  {
    $this->authSvc = new AuthService();
    $this->provider = new MockAuthProvider(MockHostConfiguration::get());
  }

  


}
