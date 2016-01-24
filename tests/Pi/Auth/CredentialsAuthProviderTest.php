<?hh

use Pi\ServiceModel\BasicRegisterRequest;
use Pi\ServiceModel\BasicRegisterResponse;
use Pi\Auth\AuthService;
use Pi\Auth\CredentialsAuthProvider;
use Pi\Auth\AuthUserSession;
use Mocks\MockHostConfiguration;
use Pi\Common\RandomString;

class CredentialsAuthProviderTest extends \PHPUnit_Framework_TestCase {


  protected $provider;

  protected $authSvc;

  public function setUp()
  {
    $this->authSvc = new AuthService();
    $this->authSvc->init(array(new CredentialsAuthProvider(MockHostConfiguration::get(), '/realm', 'basic')), new AuthUserSession());
  }

  public function testServiceAcceptTheProvider()
  {
    $this->assertNotNull($this->authSvc->getAuthProvider('basic'));
  }

  public function testCanAuthenticate()
  {
     $account = $this->createAccount();
     
  }

  protected function createAccount()
  {
    $request = new BasicRegisterRequest();
    $request->firstName('Guilherme');
    $request->lastName('Cardoso');
    $request->displayName('Guilherme Cardoso');
    $request->email('email@guilhermecardoso.pt' . RandomString::generate(4));
    $request->password('123_123123');

    $service = $this->appHost->container->getService(new RegisterService());
    $response = $service->basicRegistration($request);
    return $request;

  }
}
