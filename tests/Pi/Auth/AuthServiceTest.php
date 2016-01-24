<?hh

use Mocks\BibleHost,
    Mocks\MockHostProvider,
    Pi\HttpResult,
    Pi\SessionFactory,
    Pi\ServiceModel\AuthAuthorize,
    Pi\ServiceModel\AuthToken,
    Pi\ServiceModel\BasicRegisterRequest,
    Pi\ServiceModel\BasicRegisterResponse,
    Pi\ServiceModel\BasicAuthenticateRequest,
    Pi\ServiceModel\BasicAuthenticateResponse,
    Pi\Auth\AuthService,
    Pi\Common\RandomString,
    Pi\Auth\RegisterService,
    Mocks\HttpRequestMock;

class AuthServiceTest extends \PHPUnit_Framework_TestCase {

  protected $host;

  public function setUp()
  {
    $this->host = new BibleHost();
    $this->host->init();
  }

  public function testServiceIsRegistered()
  {
    $service = $this->host->container->tryGet(get_class(new AuthService()));
    $this->assertTrue($service instanceof AuthService);
  }

  public function testInvalidLoginsAreThrowed()
  {
    $request = $this->createBasicRequest();
    $response = MockHostProvider::execute($request);

    $req = new BasicAuthenticateRequest();
    $req->setEmail($request->email());
    $req->setPassword('123123123');
    $response = MockHostProvider::execute($req);
    $this->assertTrue($response instanceof HttpResult);
    $this->assertTrue($response->status() === 401);

  }

  public function testSessionFactoryIsRegistered()
  {
    $service = $this->host->container->getService(new AuthService());
    $this->assertTrue($service::getCurrentSessionFactory() instanceof SessionFactory);
  }

 public function testLogin()
  {
    $request = $this->createBasicRequest();
    $service = $this->host->container->getService(new RegisterService());
    $authService = $this->host->container->getService(new AuthService());
    $response = $service->basicRegistration($request);


    $req = new BasicAuthenticateRequest();
    $req->setEmail($request->email());
    $req->setPassword($request->password());
    $response = MockHostProvider::execute($req);
    $this->assertTrue($response instanceof BasicAuthenticateResponse);

    $token = $response->getAccessToken();
    $this->assertTrue($authService->validateToken($token) == $response->getUserId());

    // Validate login token

  }

  protected function createBasicRequest()
  {
    $request = new BasicRegisterRequest();
    $request->firstName('Guilherme');
    $request->lastName('Cardoso');
    $request->displayName('Guilherme Cardoso');
    $request->email('email@guilhermecardoso.pt' . RandomString::generate(4));
    $request->password('123_123123');
    return $request;
  }
}
