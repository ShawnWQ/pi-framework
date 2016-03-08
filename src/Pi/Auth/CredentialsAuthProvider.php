<?hh

namespace Pi\Auth;

use Pi\HttpResult,
    Pi\Interfaces\IService,
    Pi\Interfaces\AppSettingsInterface,
    Pi\Auth\Interfaces\IAuthSession,
    Pi\Auth\Interfaces\ICryptorProvider,
    Pi\Auth\Interfaces\IAuthTokens,
    Pi\Auth\Interfaces\IUserAuth,
    Pi\Auth\Interfaces\IUserAuthRepository;

class CredentialsAuthProvider extends AuthProvider {

  const name = 'credentials';

  const realm = '/auth/credentials';

  const preAuthUrl = 'https://www.facebook.com/dialog/oauth';

  protected ICryptorProvider $cryptor;

  public function __construct(AppSettingsInterface $appSettings, string $authRealm, string $oAuthProvider, ICryptorProvider $provider)
  {
    $this->cryptor = $provider;
    $this->provider = self::name;
    parent::__construct($appSettings, $authRealm, $oAuthProvider);
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

  public function isAuthorized(IAuthSession $session, IAuthTokens $tokens, Authenticate $request = null) : bool
  {
    if($request != null) {
      if(!self::loginMatchesSession($session, $request->getUserName())) {
        return false;
      }
    }

    return $session != null && $session->isAuthenticated() && !empty($session->getUserAuthName());
  }

  public function authenticate(IService $authService, IAuthSession $session, Authenticate $request) : ?AuthenticateResponse
  {
      $userAuth = $this->tryAuthenticate($authService, $request->getUserName() ?: $request->getEmail(), $request->getPassword());

      if(!$userAuth) {
        throw new \Exception('Not authenticated with email' . $request->getUserName() . '___' . $request->getPassword());
      }
      // if account loked throw authenticationexception
      $userAuthRepo = $authService->tryResolve('Pi\Auth\MongoDb\MongoDbAuthRepository');
      $session = $authService->getSession();
      CredentialsAuthProvider::populateSession($userAuthRepo, $userAuth, $session);
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

  public function tryAuthenticate(IService $authService, string $userName, string $password) : ?IUserAuth
  {
    $authRepo = $authService->tryResolve('Pi\Auth\MongoDb\MongoDbAuthRepository');

    if(is_null($authRepo)) {
      throw new \Exception('cant be null');
    }
    $hash = $this->cryptor->encrypt($password);

    $user = $authRepo->tryAuthenticate($userName, $hash);

    if($user === null) {
      return null;
    }
    $session = $authService->getSession();
    $session->setUserId($user->getId());
    $session->setProviderOAuthAccess($authRepo->getUserAuthDetails($session->getUserId()));
    $session->setIsAuthenticated(true);
    
    // hrow new AuthenticationException("This account has been locked");
    // set the session with the res info
    return $user;
  }

   public function onAuthenticated(IService $authService, IAuthSession $session, ?IAuthTokens $tokens, ?Map<string,string> $authInfo = null)
   {
      $authRepo = $authService->tryResolve('Pi\Auth\Interfaces\IAuthRepository');
      $hasTokens = $tokens != null && $authInfo != null;

      if($hasTokens && $session instanceof AuthUserSession) {
        $this->loadUserAuthInfo($session, $tokens, $authInfo);

        foreach ($authInfo as $key => $value) {
          $tokens->addItem($key, $value);
        }

        $userDetails = $authRepo->createOrMergeAuthSession($session, $tokens);
        //$session->setUserId($user->getId());
        $session->setUserId($userDetails->getUserId());

        foreach ($session->getProviderOAuthAccess() as $oauthToken) {
          $provider = AuthenticateService::getAuthProvider($oAuthToken->getProvider());
          if($provider == null)
            continue;

          if($provider instanceof OAuthProvider) {
            $provider->loadUserOAuthProvider($session, $oAuthToken);
          }
        }     
    }

    $httpRes = $authService->request()->response();
    if($httpRes != null) {
      // add cookie HeadersUserAuthId, session.UserAuthId
      $httpRes->cookies()->add(Pair{self::xPiUserAuthId, $session->getUserId()});
    }

    $session->setIsAuthenticated(true);
    if($tokens == null) {
      $tokens = new AuthTokens();
    }
    $session->onAuthenticated($authService, $session, $tokens, $authInfo);
    // AuthEvents
  
    $expire = new \DateTime('now');
    $expire->modify('+1 day');
    $authService->saveSession($session, $expire);   
   }
}
