<?hh
namespace Pi\Auth;

use Pi\Interfaces\IService;
use Pi\Auth\Interfaces\IAuthSession;
use Pi\Auth\Interfaces\IAuthTokens;
use Pi\Interfaces\AppSettingsInterface;
use Pi\Auth\Interfaces\IOAuthProvider,
    Pi\Auth\Interfaces\IUserAuth,
    Pi\Common\Http\HttpRequest,
    Pi\HttpRequestHeaders;
use Facebook\Facebook;

class FacebookAuthProvider extends OAuthProvider implements IOAuthProvider {

  const name = 'facebook';

  const realm = 'https://graph.facebook.com/v2.0/';

  const preAuthUrl = 'https://www.facebook.com/dialog/oauth';

  protected $fields;

  protected $defaultFields = array('id', 'name', 'first_name', 'last_name', 'email');

  protected $fbClient;

  protected $permissions;

  public function __construct(AppSettingsInterface $appSettings, ?string $appId = null, ?string $appSecret = null, ?string $accessToken = null)
  {
    $this->permissions = array('email');
    $this->provider = self::name;
    $this->appId = $appId ?: $appSettings->getString(sprintf('auth.%s.appId', self::name)) ?: 'empty';
    $this->appSecret = $appSecret ?: $appSettings->getString(sprintf('auth.%s.appSecret', self::name)) ?: 'empty';
    $this->permissions = $appSettings->getList(sprintf("auth.%s.permissions", self::name)) ?: new Set(array('email', 'public_profile'));
    $this->fields = $appSettings->getList(sprintf('auth.%s.fields', self::name)) ?: new Set($this->defaultFields);
    parent::__construct($appSettings, self::realm, self::name);
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
    $httpReq = $authService->request();

    $errors = $httpReq->parameters()->get('error_reason')
      ?: $httpReq->parameters()->get('error')
      ?: $httpReq->parameters()->get('error_code')
      ?: $httpReq->parameters()->get('error_description');
    
    $hasError = $errors != null || !empty($errors);
    if($hasError) {
      $this->log->error(sprintf('Facebok error callback. %s', print_r($httpReq->parameters(), true)));
      return;
    }

    // Request Token
    $code = $httpReq->parameters()->get('code');
    $accessToken = $httpReq->parameters()->get('accessToken');
    $isPreAuthCallback = ($code != null && !empty($code)) || ($accessToken != null && !empty($accessToken));
    $permissions = implode(',', $this->permissions);

    if(!$isPreAuthCallback) {
      $preAuthUrl = sprintf('%s?client_id=%s&redirect_uri=%s&scope=%s', self::preAuthUrl, $this->appId, OAuthUtils::urlencodeRfc3986($this->callbackUrl), $permissions);
      if($request->getContinue() != null) { // state parameter is usedy by Facebook API as an arbitrary unique string created an app to guard against Cross-site Request Forgery
        $preAuthUrl .= '&state=' . OAuthUtils::urlencodeRfc3986($request->getContinue());
      }

      $authService->saveSession($session);
      return $authService->redirect($preAuthUrl);
    }


    $requestFbAccessToken = function(string $code) {
        $accessTokenUrl = sprintf('%soauth/access_token?client_id=%s&redirect_uri=%s&client_secret=%s&code=%s', self::realm, $this->appId, OAuthUtils::urlencodeRfc3986($this->callbackUrl), $this->appSecret, $code);
        $httpReq = new HttpRequest($accessTokenUrl, 'GET');
        $msg = $httpReq->send();
        return substr($msg->getBody(), 13);
    };

    if($accessToken != null && !empty($accessToken)) {
      $httpReq = new HttpRequest(self::realm . '/me?access_token=' . $authService->request()->parameters()->get('accessToken'));
      try {
        $msg = $httpReq->send();
        $json = $authService->request()->parameters()->get('accessToken');
        $obj = json_decode($json);
        if($obj == null || property_exists($obj, 'error')) {
          throw new \Exception('Error returned from Access Token validation.');
        }
        $accessToken = $obj->accessToken;
      }
      catch(\Exception $ex) {
        $this->log->error('Error validating the Facebook Access Token: ' . $ex->getMessage());
        // validate access token
      } 
    } else {
        $accessToken = $this->requestFbAccessToken();
    }

    if(empty($accessToken)) {
      throw new \Exception('Couldnt obtain access token');
    }

    
    if($tokens == null) {
      $tokens = new AuthTokens();
    }
    $tokens->setProvider(self::name);
    $tokens->setAccessTokenSecret($accessToken);
    $session->setIsAuthenticated(true);
    $response = $this->onAuthenticated($authService, $session, $tokens, new Map(Pair{'access_token', $accessToken}));
    
    if($authService->request()->parameters()->contains('state')) {
      $redirectUri = $authService->request()->parameters()->get('state');
      return $authService->redirect($redirectUri);
    }
    
    $referrerUrl = '/';
    return new AuthenticateResponse(
      $session->getUserId(),
      $session->getUserAuthName() ?: $session->getUserName() ?: sprintf("{0} {1}", $session->getFirstName(), $session->getLastName()),
      $session->getDisplayName(),
      $session->getId(),
      $referrerUrl
    );
  }

  const FACEBOOK_USER_URL = 'https://graph.facebook.com/v2.0/me?access_token=%s';

  const FACEBOOK_PROFILE_URL = 'https://facebook.com/%s';

  public static function downloadFacebookUserInfo(string $accessTokenSecret, Set $fields) : string
  {
    if(empty($accessTokenSecret)) {
      throw new \Exception('Access Token Secret cant be empty.');
    }
    $url = sprintf(self::FACEBOOK_USER_URL, $accessTokenSecret) ;
    if($fields->count() > 0) {
      $url .= '&fields=' . implode(',', $fields);
    }
    $request = new HttpRequest($url);
    $msg = $request->send();
    $json = $msg->getBody();
    return $json;
  }

  public static function getFacebookUsernameFromId($facebookId) : string
  {
    $request = new HttpRequest();
    $uri = sprintf(self::FACEBOOK_PROFILE_URL, $facebookId);
    $msg = $request->send();
    $json = $msg->getBody();
    return $json;
  }

  public function loadUserAuthInfo(AuthUserSession $userSession, IAuthTokens $tokens, Map<string,string> $authInfo)
  {
    $json = self::downloadFacebookUserInfo($tokens->getAccessTokenSecret(), $this->fields);
    $obj = json_decode($json);
    $tokens->setUserId($obj->id);
    //$tokens->setUserName($obj->username);
    $tokens->setDisplayName($obj->name);
    $tokens->setFirstName($obj->first_name);
    $tokens->setLastName($obj->last_name);
    
    if(property_exists($obj, 'email')) {
      $tokens->setEmail($obj->email);
    }
    

    $this->loadUserOAuthProvider($userSession, $tokens);
  }

  public function loadUserOAuthProvider(IAuthSession $authSession, IAuthTokens $tokens) 
  {
    $authSession->setFacebookUserId($tokens->getUserId() ?: $authSession->getFacebookUserId());
    //$authSession->setFacebookUserName($tokens->getUserName() ?: $authSession->getFacebookUserName());
    $authSession->setDisplayName($tokens->getDisplayName() ?: $authSession->getDisplayName());
    $authSession->setFirstName($tokens->getFirstName() ?: $authSession->getFirstName());
    $authSession->setLastName($tokens->getLastName() ?: $authSession->getLastName());
    $authSession->setPrimaryEmail($tokens->getEmail() ?: $authSession->getPrimaryEmail() ?: $authSession->getEmail() ?: '');
  }

  public function logout(IService $service, Authenticate $request)
  {

  }

  public function isAuthorized(IAuthSession $session, IAuthTokens $tokens, Authenticate $request = null) : bool
  {
    return false;
  }
}
