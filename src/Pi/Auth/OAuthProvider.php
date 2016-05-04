<?hh
namespace Pi\Auth;

use Pi\Interfaces\IService,
    Pi\Interfaces\AppSettingsInterface,
    Pi\Auth\Interfaces\IOAuthProvider,
    Pi\Auth\Interfaces\IAuthSession,
    Pi\Auth\Interfaces\IAuthTokens;



abstract class OAuthProvider extends AuthProvider  {

  public function __construct(AppSettingsInterface $appSettings, string $authRealm, string $oAuthProvider)
  {
    parent::__construct($appSettings, $authRealm, $oAuthProvider);
    $this->callbackUrl = $appSettings->getString(sprintf("auth.%s.callbackUrl", $oAuthProvider)) ?: 'http://localhost/auth/facebook';
    $this->accessTokenUrl = $appSettings->getString(sprintf('auth.%s.accessTokenUrl', $oAuthProvider)) ?: $this->getRealm() . '/oauth/access_token';
  }

  protected string $consumerKey;

  protected string $consumerSecret;

  protected string $requestTokenUrl;

  protected string $authorizeUrl;

  protected string $accessTokenUrl;

  public function getConsumerKey() : string
  {
    return $this->consumerKey;
  }

  public function getConsumerSecret() : string
  {
    return $this->consumerSecret;
  }

  public function setConsumerSecret(string $value) : void
  {
    $this->consumerSecret = $value;
  }

  public function getRequestTokenUrl() : string
  {
    return $this->requestTokenUrl;
  }

  public function setRequestTokenUrl(string $value) : void
  {
    $this->requestTokenUrl = $value;
  }

  public function getAuthorizeUrl() : string
  {
    return $this->authorizeUrl;
  }

  public function setAuthorizeUrl(string $value) : void
  {
    $this->authorizeUrl = $value;
  }

  public function getAccessTokenUrl() : string
  {
    return $this->accessTokenUrl;
  }

  public function setAccessTokenUrl(string $value) : void
  {
    $this->accessTokenUrl = $value;
  }

  public function init(IService $authService, IAuthSession &$session, Authenticate $request) : IAuthTokens
  {
    $requestUri = $authService->request()->requestUri();
    if((empty($this->callbackUrl))) {
      $this->callbackUrl = $requestUri;
    }

    $ac = $session->getProviderOAuthAccess();
    $tokens = array_key_exists($this->provider, $ac) ? $ac[$this->provider] : null;
    if(is_null($tokens)) {
      $tokens = new AuthTokens();
      $session->addProviderOAuthAccess($tokens);
    }

    return $tokens;
  }

  public function loadUserOAuthProvider(IAuthSession $authSession, IAuthTokens $tokens)  { }
}
