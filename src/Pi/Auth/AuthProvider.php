<?hh

namespace Pi\Auth;
use Pi\Service;
use Pi\EventManager;
use Pi\Interfaces\AppSettingsInterface;
use Pi\Interfaces\IService;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse,
    Pi\Interfaces\IHttpResponse;
use Pi\Auth\Interfaces\IAuthSession;
use Pi\Auth\Interfaces\IAuthTokens;
use Pi\Auth\Interfaces\IUserAuth;
use Pi\Auth\Interfaces\IUserAuthRepository;
use Pi\Auth\Authenticate;

abstract class AuthProvider {

  public EventManager $eventManager;

  public MongoDbAuthUserRepository $repository;

  //public abstract function get(Auth $auth);

  //public function create(Auth $auth);

  //public function remove(Auth $auth);

  protected $sessionExpire;

  protected $authRealm;

  protected $provider;

  protected $callbackUrl;

  protected $redirectUrl;
  
  public function __construct(AppSettingsInterface $appSettings, string $authRealm, string $oAuthProvider)
  {
    $this->authRealm = is_null($appSettings) || !$appSettings->exists('OAuthRealm') ? $authRealm : $appSettings->getString('OAuthRealm', $authRealm);
    $this->provider = $oAuthProvider;

    if(!is_null($appSettings)) {
      // @todo set redirect and callback url
    }
  }


  public abstract function authenticate(IService $authService, IAuthSession $session, Authenticate $request) : ?IUserAuth;

  public abstract function isAuthorized(IAuthSession $session, IAuthTokens $tokens, Authenticate $request = null) : bool;

  public function loadAuthInfo(AuthUserSession $userSession, IAuthTokens $tokens, Map<string,string> $authInfo)
  {

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
  
  static function handleFailedAuth(IAuthProvider $authProvider, IAuthSession $authSession, IRequest $request, IResponse $response)
  {

    // $request->endRequest();
  }

  public static function populateSession(IUserAuthRepository $authRepo, IUserAuth $userAuth, IAuthSession $session)
  {
    $cacheId = $session->getId();
    AuthExtensions::populateSessionWithUserAuth($session, $userAuth);
    $session->setId($cacheId);
    $session->setUserId($userAuth->getId());
    $session->setProviderOAuthAccess($authRepo->getUserAuthDetails($session->getUserId()));
    $session->setUsername($userAuth->getUsername());
    $session->setDisplayName($userAuth->getDisplayName());
  }

  public function emailAlreadyExists(IAuthRepository $authRepo, IUserAuth $userAuth, ?IAuthTokens $tokens = null)
  {

  }

  public function userNameAlreadyExists(IUserAuthRepository $authRepo, IUserAuth $userAuth, ?IAuthTokens $tokens = null)
  {
    if(is_null($tokens) && is_null($tokens->getUserName())) {
      return false;
    }

    $userExisting = $this->authRepo->getUserAuthByUserName($tokens->getUserName());
    if(is_null($userExisting)) {
      return false;
    }

    return is_null($userAuth) ? false : $userAuth->getId() !== $userExisting->getId();
  }

  protected function isAccountLocked(IAuthRepository $authRepo, IUserAuth $userAuth, IAuthTokens $tokens = null)
  {
    if(is_null($userAuth)) {
      return false;
    }

    return !is_null($userAuth->getLockedDate());
  }

  protected function validateToken()
  {
    // Email already exist
    // UserName already exists
    // Account locked
  }

  public function getSessionExpire()
  {
      return $this->sessionExpire;
  }

  public function getAuthRealm() : string
  {
    return $this->authRealm;
  }

  public function getProvider() : string
  {
    return $this->provider;
  }

  public function getCallbackUrl() : string
  {
    return $this->callbackUrl;
  }

  public function logout(IService $service, Auth $request)
  {
    $session = $service->getSession();
    $session->onLogout($service);
    AuthEvents::onLogout($service->request(), $session, $service);
    $service->removeSession();
  }

  public function onAuthenticated(IService $authService, IAuthSession $session, IAuthTokens $tokens, array $authInfo = null)
  {

  }

  public function saveUserAuth(IService $authService, IAuthSession $session, IAuthRepository $authRepo, ?IAuthTokens $tokens = null) : void
  {

    if($authRepo == null) return;
    if($tokens != nul)  {
      $user = $authRepo->createOrMergeAuthSession($sessio, $tokens);
      $session->setUserId($user->getUserId());
    }

    $authRepo->loadUserAuth($session, $tokens);
    $httpRes = $authService->request()->response();
    if($httpRes instanceof IHttpResponse) {
      // add cookies
    }
    $this->onSaveUserAuth($authService, $session);
  }

  public function onSaveUserAuth(IService $authService, IAuthSession $session)
  {

  }

  static function loginMatchesSession(IAuthSession $session, string $userName) : bool
  {
    if($session == null || empty($userName))
      return false;

    $isEmail = strpos($userName, '@') !== false;
    if($isEmail) {
      if($userName != $session->getEmail())
        return false;
    }
    else {
      if($userName != $session->getUserAuthName())
        return false;
    }
    return true;
  }
}
