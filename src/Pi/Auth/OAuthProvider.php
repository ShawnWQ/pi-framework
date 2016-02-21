<?hh
namespace Pi\Auth;

use Pi\Auth\Interfaces\IOAuthProvider;



abstract class OAuthProvider extends AuthProvider  {

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

  public function init(IService $authService, IAuthSession &$session, Auth $request) : IAuthTokens
  {
    $requestUri = $authService->request()->requestUri();
    if((!empty($this->callbackUrl))) {
      $this->callbackUrl = $requestUri;
    }

    $ac = $session->getProviderOAuthAccess();
    $tokens = !array_key_exists($this->provider, $ac) ? $ac : null;
    if(is_null($tokens)) {
      $tokens = new OAuthTokens();
      $session->addProviderOAuthAccess($tokens);
    }

    return $tokens;
  }

  public function ladUserOAuthProvider(IAuthSession $authSession, IAuthTokens $tokens) {
    
  }
}
