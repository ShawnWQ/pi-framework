<?hh
namespace Test\Auth;


use Mocks\MockHostConfiguration,
    Mocks\BibleHost,
    Test\Auth\BaseAuthTest,
    Pi\HostConfig,
    Pi\ServiceModel\BasicRegisterRequest,
    Pi\ServiceModel\BasicRegisterResponse,
    Pi\Common\RandomString,
    Pi\Auth\AuthService,
    Pi\Auth\Md5CryptorProvider,
    Pi\Auth\Authenticate,
    Pi\Auth\CredentialsAuthProvider,
    Pi\Auth\AuthUserSession;




class CredentialsAuthProviderTest extends BaseAuthTest {


  protected $provider;

  protected $authSvc;

  protected $host;

  public function setUp()
  {
    $this->host = new BibleHost();
    $this->host->init();
    $this->authSvc = $this->host->serviceController()->getServiceInstance('Pi\Auth\AuthService');
    $this->provider = $this->authSvc->getAuthProvider(CredentialsAuthProvider::name);
  }

  public function testServiceAcceptTheProvider()
  {
    $this->assertNotNull($this->provider);
  }

  public function testCanAuthenticate()
  {
    $authRepo = $this->getAuthRepository();
    $cryptor = $this->getCryptor();
    $user = $this->createUserAuth();
    $userDb = $authRepo->createUserAuth($user, $cryptor->encrypt('123'));
    
    $session = $this->authSvc->getSession();
    $this->assertFalse($session->isAuthenticated());

    $request = new Authenticate();
    $request->setUserName($user->getEmail());
    $request->setPassword('123');
    $session = $this->createAuthUserSession();
    $res = $this->provider->authenticate($this->authSvc, $session, $request);
    $this->assertEquals($res->getDisplayName(), $userDb->getDisplayName());
    
    $session = $this->authSvc->getSession();
    $this->assertTrue($session->isAuthenticated());
  }
}