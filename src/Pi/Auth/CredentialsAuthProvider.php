<?hh

namespace Pi\Auth;

use Pi\HttpResult;
use Pi\Interfaces\IService;
use Pi\Auth\Interfaces\IAuthSession;
use Pi\Auth\Interfaces\IAuthTokens;
use Pi\Interfaces\IHostConfig;

class CredentialsAuthProvider extends AuthProvider {

  const name = 'credentials';

  const realm = '/auth/credentials';

  public function __construct(IHostConfig $appSettings, string $authRealm, string $oAuthProvider)
  {
    $this->provider = self::name;
    parent::__construct($appSettings, $authRealm, $oAuthProvider);
  }

  public function getRealm()
  {
    return self::realm;
  }

  public function getName()
  {
    return self::name;
  }

  public function isAuthorized(IAuthSession $session, IAuthTokens $tokens, Authenticate $request = null)
  {

  }

  public function authenticate(IService $authService, IAuthSession $session, Authenticate $request)
  {
    try {
      return $this->tryAuthenticate($authService, $request->getUserName(), $request->getPassword());
    }
    catch(\Exception $ex) {
      return null;
    }
  }


  public function tryAuthenticate(IService $authService, string $userName, string $password)
  {
    $authRepo = $authService->tryResolve('Pi\Auth\MongoDb\MongoDbAuthRepository');
    $userRepo = $authService->tryResolve('Pi\Auth\MongoDbAuthUserRepository');
    if(is_null($userRepo) || is_null($authRepo)) {
      throw new \Exception('cant be null');
    }
    $user = $userRepo->getByEmailAndPw($userName, $password);
    if($user === null) {
      return false;
    }
    $session = $authService->getSession();
    if(!is_null($user)) {
      // populate session, set is authenticated, id,
      $session->setUserId($user->getId());
      //$session->setProviderOAuthAccess($authRepo->getUserAuthDetails($session->getUserId()));
      $session->setIsAuthenticated(true);
    }
    // hrow new AuthenticationException("This account has been locked");
    // set the session with the res info
    return $user;
  }

   public function onAuthenticated(IService $authService, IAuthSession $session, IAuthTokens $tokens, array $authInfo = null)
   {
     $authRepo = $authService->tryResolve('Pi\Auth\Interfaces\IAuthRepository');

     if($session instanceof AuthUserSession) {
          if(!is_null($tokens)) {
            $user = $authRepo->createOrMergeAuthSession($session, $tokens);
            $session->setUserId($user->getId());
          }
     }
     /*


             foreach (var oAuthToken in session.ProviderOAuthAccess)
             {
                 var authProvider = AuthenticateService.GetAuthProvider(oAuthToken.Provider);
                 if (authProvider == null)
                 {
                     continue;
                 }
                 var userAuthProvider = authProvider as OAuthProvider;
                 if (userAuthProvider != null)
                 {
                     userAuthProvider.LoadUserOAuthProvider(session, oAuthToken);
                 }
             }

             var httpRes = authService.Request.Response as IHttpResponse;
             if (httpRes != null)
             {
                 httpRes.Cookies.AddPermanentCookie(HttpHeaders.XUserAuthId, session.UserAuthId);
             }

             var failed = ValidateAccount(authService, authRepo, session, tokens);
             if (failed != null)
                 return failed;
         }

         try
         {
             session.IsAuthenticated = true;
             session.OnAuthenticated(authService, session, tokens, authInfo);
             AuthEvents.OnAuthenticated(authService.Request, session, authService, tokens, authInfo);
         }
         finally
         {
             authService.SaveSession(session, SessionExpiry);
         }

         return null;
         */
   }
}
