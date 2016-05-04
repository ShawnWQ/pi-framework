<?hh
use Pi\Auth\AuthService;
use Pi\Auth\FacebookAuthProvider;
use Pi\Auth\AuthUserSession;
use Mocks\MockHostConfiguration;


class FacebookAuthProviderTest extends \PHPUnit_Framework_TestCase {


  protected $authSvc;

  protected $FB_SECRET = 'mocked';

  public function setUp()
  {
    $this->authSvc = new AuthService();
    $config = MockHostConfiguration::get();
    $config->oAuths(FacebookAuthProvider::oauthConfig('$appId', '$appSecret'));
    $this->authSvc->init(array(new FacebookAuthProvider($config, '/realm', 'facebook', $this->FB_SECRET,)), new AuthUserSession());
  }

  public function testServiceAcceptTheProvider()
  {
    $this->assertNotNull($this->authSvc->getAuthProvider('facebook'));
  }
  public function testCanCreateOAuthConfig()
  {
    $res = FacebookAuthProvider::oauthConfig('id', 's');
    $this->assertTrue(is_array($res));
    $this->assertTrue($res['appSecret'] === 's' && $res['appId'] === 'id');
  }

  public function testCanAuthenticate()
  {
    $provider = $this->authSvc->getAuthProvider('facebook');

  }
}
