<?hh

namespace Pi\Auth;
use Pi\Service;
use Pi\EventManager;
use Pi\Interfaces\IHostConfig;
use Pi\Interfaces\IService;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\Auth\Interfaces\IAuthSession;
use Pi\Auth\Interfaces\IAuthTokens;
use Pi\Auth\Interfaces\IUserAuthRepository;
use Pi\Auth\Authenticate;

abstract class AuthProvider {

  public function __construct(IHostConfig $appSettings, string $authRealm, string $oAuthProvider)
  {
    $this->authRealm = is_null($appSettings) ? $authRealm : $appSettings->get('OAuthRealm', $authRealm);
    $this->provider = $oAuthProvider;

    if(!is_null($appSettings)) {
      // @todo set redirect and callback url
    }
  }

  public EventManager $eventManager;

  public  MongoDbAuthUserRepository $repository;

  //public abstract function get(Auth $auth);

  //public function create(Auth $auth);

  //public function remove(Auth $auth);

  protected $sessionExpire;

  protected $authRealm;

  protected $provider;

  protected $callbackUrl;

  protected $redirectUrl;

  public abstract function authenticate(IService $authService, IAuthSession $session, Authenticate $request);

  public abstract function isAuthorized(IAuthSession $session, IAuthTokens $tokens, Authenticate $request = null);
  
  static function handleFailedAuth(IAuthProvider $authProvider, IAuthSession $authSession, IRequest $request, IResponse $response)
  {

    // $request->endRequest();
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


}
