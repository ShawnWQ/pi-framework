<?hh
namespace Mocks;

use Pi\Interfaces\IService;
use Pi\Auth\Interfaces\IAuthSession;
use Pi\Auth\Interfaces\IAuthTokens;
use Pi\Interfaces\IHostConfig;
use Pi\Auth\Interfaces\IOAuthProvider,
    Pi\Auth\Interfaces\IUserAuth,
    Pi\Auth\UserAuth,
    Pi\Auth\UserAuthDetails;
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


  /**
   * Endpoint called by FB
   *
   */
  public function authenticate(IService $authService, IAuthSession $session, Authenticate $request) : ?IUserAuth
  {
    $userAuth = $this->tryAuthenticate($authService, $request->getUserName(), $request->getPassword());
    $userAuth = new UserAuth();
    $userAuth->setEmail($request->getUserName());
    $userAuth->setFirstName('Guilherme');
    $userAuth->setLastName('Cardoso');
    $userAuth->setDisplayName('Guilherme Cardoso');
    $userAuth->setEmail('email@guilhermecardoso.pt');
    $userAuth->setUsername('email@guilhermecardoso.pt');
    $userAuth->setId(new \MongoId());

    $details = array();
    $detail = new UserAuthDetails();
    $detail->setUserId($userAuth->getId());
    $details[] = $detail;

    $session = $authService->getSession();
      
    $cacheId = $session->getId();
    AuthExtensions::populateSessionWithUserAuth($session, $userAuth);
    $session->setId($cacheId);
    $session->setUserId($userAuth->getId());
    $session->setProviderOAuthAccess($details);
    $session->setUsername($userAuth->getUsername());
    $session->setDisplayName($userAuth->getDisplayName());

    $referrerUrl = '';

    $response = $this->onAuthenticated($authService, $session, null, null);

    return new AuthenticateResponse(
      $session->getUserId(),
      $session->getUserAuthName() ?: $session->getUserName() ?: sprintf("{0} {1}", $session->getFirstName(), $session->getLastName()),
      $session->getDisplayName(),
      $session->getId(),
      $referrerUrl
    );
  }

  public function logout(IService $service, Authenticate $request)
  {

  }

  public function isAuthorized(IAuthSession $session, IAuthTokens $tokens, Authenticate $request = null) : bool
  {
    return false;
  }
}
