<?hh

namespace Pi\Auth;
use Pi\Service,
    Pi\HttpError,
    Pi\ErrorMessages,
    Pi\Interfaces\IRequest;
use Pi\HttpResult;
use Pi\Common\RandomString;
use Pi\Auth\Interfaces\IAuthSession,
    Pi\Auth\Interfaces\IAuthRepository,
    Pi\Auth\Interfaces\IAuthUserRepository,
    Pi\Auth\Interfaces\IAuthDetailsRepository,
    Pi\Interfaces\HasSessionIdInterface;
use Pi\ServiceModel\AuthRedisKeys;
use Pi\ServiceModel\AuthAuthorize;
use Pi\ServiceModel\AuthAuthorizeResponse;
use Pi\ServiceModel\AuthSession;
use Pi\ServiceModel\AuthToken;
use Pi\ServiceModel\AuthTokenResponse;
use Pi\ServiceModel\BasicAuthenticateRequest;
use Pi\ServiceModel\BasicAuthenticateResponse;
use Pi\Redis\Interfaces\IRedisClientsManager;

class AuthService extends Service {

  const logoutAction = 'logout';

  public IAuthUserRepository $repository;

  public IAuthDetailsRepository $detailsRepository;

  public IAutRepository $authRepository;

  public AuthConfig $config;

  protected static $authProviders = array();

  protected static $currentSessionFactory = null;

  protected static $defaultOAuthProvider;

  protected static $defaultOAuthRealm;

  /**
   * @return \Pi\Auth\Interfaces\IAuthSession
   */
  static function getCurrentSessionFactory() : IAuthSession
  {
    return self::$currentSessionFactory;
  }

  static function getAuthProvider(string $provider)
  {
    if(is_null(self::$authProviders) || count(self::$authProviders) === 0) {
      return null;
    }

    if($provider === self::logoutAction) {
      return $this->authProviders[0];
    }

    foreach(self::$authProviders as $authProvider) {
      if($provider === $authProvider->getProvider()) {
        return $authProvider;
      }
    }

    return null;
  }

  static function populateFromRequestIfHasSessionId(IRequest $req, $dto) : bool {
    if($dto instanceof HasSessionIdInterface) {
      $req->setSessionId($dto->getSessionId());
      return true;
    }
    return false;
  }

  static function hasAuthProviders() : bool
  {
    return self::$authProviders != null && count(self::$authProviders) > 0;
  }

  static function init(array $authProviders, $sessionFactory) : void
  {
    if(count($authProviders) === 0) {
      throw new \Exception('no authproviders');
    }

    self::$defaultOAuthProvider = $authProviders[0];
    self::$defaultOAuthRealm = $authProviders[0]->getRealm();
    self::$authProviders = $authProviders;
    if(!is_null($sessionFactory)) {
      self::$currentSessionFactory = $sessionFactory;
    }


  }

  public function saveSession($session, ?\DateTime $expires = null)
  {
    $this->request()->saveSession($session, $expires);
  }

  // When you confirm the form, the server creates a temporary token (auth token as they're called), which typically has a very short life (my oauth2 sp code typically sets this to 60 seconds). This is the time your server has to go from receiving the code to triggering step 2.
  // It is just a confirmation system, and its purpose is to also store the info provided in step 1 to prevent hijacks.
  /**
   * To obtain a AuthToken to an app client the user must be authenticated
   */
  <<Request, Auth, Route('/oauth/authorize'), Method('POST')>>
  public function getAuthorization(AuthAuthorize $request)
  {
    $authToken = $this->createAuthToken($request->getClientId(), $request->getScope());
    $response = new AuthAuthorizeResponse($authToken->getCode());
    return $response;
  }

  // This is where your access token is actually created. Lots of verifications, lots of stuff, but in the end, the token is just a value that links your client_id and your token. That's all it is.
  <<Request, Auth, Route('/token'), Method('POST')>>
  public function getToken(AuthToken $request)
  {
    if(!$this->validateAuthToken($request->getClientId(), $request->getScope(), $request->getCode())){
      return HttpResult::createCustomError('InvalidToken', _('InvalidToken'));
    }

    $token = $this->getTokenFromRedis($request->getClientId());

    $response = new AuthTokenResponse($token);
    return $response;
  }

  public function validateToken(string $token)
  {
    $id = $this->getUserIdByToken($token);
    if(is_null($id) || !\MongoId::isValid($id)) {
      return false;
    }
    return new \MongoId($id);
  }

  /**
   * Generates a token by email and password
   */
  <<Request,Route('/login'),Method('POST')>>
  public function basicAuthenticate(BasicAuthenticateRequest $request)
  {
    $r = new Authenticate();

    $r->setUserName($request->getEmail());
    $r->setPassword($request->getPassword());


    $user = self::$defaultOAuthProvider->authenticate($this, $this->request()->getSession(), $r);
    if(is_null($user) || $user === false)  {
      return HttpResult::createCustomError('InvalidEmailOrPw', _('InvalidEmailOrPw'));
    }

    // Generate a authentication token
    $authReq = new AuthAuthorize('', $user->id(), '', 'login');
    $tokenReq = $this->getAuthorization($authReq);
    $token = $this->getToken(new AuthToken('', $user->id(), 'read write update', 'login', $tokenReq->getCode()));
    $response = new BasicAuthenticateResponse($token->getCode(), $user->id());
    
    $v = json_encode(array('id' => (string)$user->id(), 'token' => $token->getCode()));
    setcookie("Authorization", $token->getCode(), time() + 60*60*24*365, '/', $this->appConfig()->domain());


    return $response;

  }

  /*public function login(Authenticate $request)
  {
    $provider = empty($request->getProvider()) ? self::$defaultOAuthProvider->getName() : $request->getProvider();
    $oauthProvider = $this->getAuthProvider($provider);

    if($oauthProvider == null)
      throw HttpError::notFound(ErrorMessages::unknownAuthProviderFmt(''));
    // if request.rememberMe add request.addSessionOptions
    $session = $this->getSession();

    $response = $this->doAuthenticate($request, $provider, $session, $oauthProvider);
    try {
      
      $response = $this->doAuthenticate($request, $provider, $session, $oauthProvider);
      $session = $this->getSession();
      if($request->getProvider() == nul && !$session->isAuthenticated()) {
        throw HttpError::unauthorized(ErrorMessages::$notAuthenticated);
      }

      //referUrl request.continue session.refferUrl request->header("Referer") oauthConfig->callbackUrl
      $referrerUrl = '';

      if($response == null)
        $response = new AuthenticateResponse(
          $session->getUserId(),
          $session->getUserAuthName() ?: $session->getUserName() ?: sprintf("{0} {1}", $session->getFirstName(), $sesssion->getLastName()),
          $session->getId(),
          $referrerUrl
        );
     
    }
    catch(\Exception $exception) {
      // check if redirect
      throw $ex;
    }
    // do logout
  }
*/
  <<Request,Route('/auth/:provider'),Method('GET')>>
  public function authenticate(Authenticate $request)
  {
    $provider = $request->getProvider() ?: self::$defaultOAuthProvider->getName();
    $oauthProvider = self::getAuthProvider($provider);

    if($oauthProvider == null)
      $oauthProvider = self::$defaultOAuthProvider;
      //throw HttpError::notFound('invalid provider format' . $provider);

    // if request.remember me add request.addsessionptions
    // check if the request provider is logout, then call the logout method on the $oauthProvider
    $session = $this->getSession();
    $response = $this->doAuthenticate($request, $provider, $session, $oauthProvider);
    
    if($response instanceof HttpResult) {
      return $response;
    }
    if($response == null)
      return HttpResult::createCustomError('InvalidLogin', _('InvalidLogin'));
    
    return $response;
  }

  protected function doAuthenticate(Authenticate $request, string $provider, $session, $oauthProvider)
  {

    if(empty($request->getProvider()) && empty($request->getEmail()))
      return null;

    $session = $this->getSession();

    $response = null;
    if(AuthExtensions::getOAuthTokensFromSession($session, $provider) == null ||
      !$oauthProvider->isAuthorized($session, AuthExtensions::getOAuthTokensFromSession($session, $provider), $request)) {
     //$this->request()->getSession()
      //if($generateCookies) // request.generateNewSessionCookies
      $response = $oauthProvider->authenticate($this, $session, $request);
    } else {
      die('no oauth');
      // if $generateCookies request->generateNewSessionCookies request->saveSession($session)
    }
    return $response;
  }
  /*
   * public because tests
   */
  public function createAuthToken($clientId, $scope) : AuthToken
  {
    $authToken = new AuthToken('token', $clientId);
    $authToken->setCode(RandomString::generate(20));
    // expire in redis
    $redis = $this->redisClient();
    $redisSet = sprintf(AuthRedisKeys::Token, (string)$clientId);
    $redis->set($redisSet, $authToken->getCode());
    $user = $redis->hgetAll('user::' . (string)$clientId);
    $redis->set('user_token::' . $authToken->getCode(), json_encode(array(
      'id' => (string)$clientId,
      'name' => !is_array($user) || !array_key_exists('name', $user) ? '' : $user['name'],
      'roles' => [])));

    return $authToken;
  }

  public function getUserIdByToken(string $token)
  {
    return json_decode($this->redisClient()->get('user_token::' . $token), true)['id'];
  }

  public function getUserRedisByToken(string $token)
  {
    return json_decode($this->redisClient()->get('user_token::' . $token), true);
  }

  protected function validateAuthToken($clientId, $scope, $code) : bool
  {
    $redis = $this->redisClient();
    $token = $this->getTokenFromRedis($clientId);
    return $token === $code;
  }

  protected function getTokenFromRedis($clientId)
  {
    return $this->redisClient()->get(sprintf(AuthRedisKeys::Token, (string)$clientId));
  }
}
