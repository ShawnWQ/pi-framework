<?hh

use Pi\ServiceModel\BasicRegisterRequest;
use Pi\ServiceModel\BasicRegisterResponse;
use Pi\Auth\AuthService,
    Pi\Auth\Md5CryptorProvider,
    Pi\HostConfig;
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
    $this->provider = new CredentialsAuthProvider(MockHostConfiguration::get(), '/realm', 'basic', new Md5CryptorProvider());
    $this->authSvc->init(array($this->provider), new AuthUserSession());
  }

  public function testServiceAcceptTheProvider()
  {
    $this->assertNotNull($this->authSvc->getAuthProvider('basic'));
  }

  public function testCanAuthenticate()
  {
    /*$session = new AuthUserSession()
    $req = new Authenticate();
    $req->setEmail('asd@asd.com');
    $req->setPassword($pw);
    return $this->queryBuilder()
      ->hydrate()
      ->field('email')->eq($email)
      ->field('password')->eq($passwordHash)
      ->getQuery()
      ->getSingleResult();
      */
    //$provider->authenticate()$this->authSvc, new AuthUserSession(), 
  }
}
