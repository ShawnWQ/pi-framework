<?hh
namespace Pi\Auth;

use Pi\Interfaces\IService;
use Pi\Auth\Interfaces\IAuthSession;
use Pi\Auth\Interfaces\IAuthTokens;
use Pi\Interfaces\IHostConfig;
use Pi\Auth\Interfaces\IOAuthProvider;
use Facebook\Facebook;

class FacebookAuthProvider extends OAuthProvider implements IOAuthProvider {

  const name = 'facebook';

  const realm = 'https://graph.facebook.com/v2.0/';

  const preAuthUrl = 'https://www.facebook.com/dialog/oauth';

  protected $fbClient;


  public function __construct(IHostConfig $appSettings, string $authRealm, string $appId, string $appSecret, ?string $accessToken = null)
  {
    $this->provider = self::name;
    parent::__construct($appSettings, $authRealm, 'facebook');
    $this->fbClient = new Facebook([
      'app_id' => $auth['appId'],
      'app_secret' => $auth['appSecret'],
      'default_graph_version' => 'v2.4',
      'default_access_token' => $accessToken,
    ]);
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
  public function authenticate(IService $authService, IAuthSession $session, Authenticate $request)
  {
    //$request = new HttpRequest(self::preAuthUrl, HttpRequest::METHOD_POST);
    $tokens = $this->init($authService, $session, $request);

  }

  public function logout(IService $service, Authenticate $request)
  {

  }

  public function isAuthorized(IAuthSession $session, IAuthTokens $tokens, Authenticate $request = null)
  {

  }
}
