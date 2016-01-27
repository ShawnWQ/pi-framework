<?hh
namespace Mocks;

use Pi\Interfaces\IService;
use Pi\Auth\Interfaces\IAuthSession;
use Pi\Auth\Interfaces\IAuthTokens;
use Pi\Interfaces\IHostConfig;
use Pi\Auth\Interfaces\IOAuthProvider,
    Pi\Auth\Interfaces\IUserAuth;
use Facebook\Facebook;

class MockAuthProvider extends OAuthProvider implements IOAuthProvider {

  const name = 'mock';

  const realm = 'https://graph.facebook.com/v2.0/';

  const preAuthUrl = 'https://www.facebook.com/dialog/oauth';

  protected $fbClient;


  public function __construct(IHostConfig $appSettings)
  {
    $this->provider = self::name;
    parent::__construct($appSettings, 'realm', 'mock');
  }

  public static function oauthConfig(string $appId, string $appSecret)
  {
    return array(
      'appId' => $appId,
      'appSecret' => $appSecret
    );
  }

  public function getOAuthRealm()
  {
    return self::realm;
  }

  public function getPreAuthUrl()
  {
    return self::preAuthUrl;
  }

  public function getRealm()
  {
    return self::realm;
  }

  public function getName()
  {
    return self::name;
  }

  /**
   * Endpoint called by FB
   *
   */
  public function authenticate(IService $authService, IAuthSession $session, Authenticate $request) : ?IUserAuth
  {
    //$request = new HttpRequest(self::preAuthUrl, HttpRequest::METHOD_POST);
    $tokens = $this->init($authService, $session, $request);

  }

  public function logout(IService $service, Authenticate $request)
  {

  }

  public function isAuthorized(IAuthSession $session, IAuthTokens $tokens, Authenticate $request = null) : bool
  {
    return false;
  }
}
