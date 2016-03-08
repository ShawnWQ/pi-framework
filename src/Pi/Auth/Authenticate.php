<?hh

namespace Pi\Auth;

use Pi\Interfaces\IMeta;

class Authenticate {

  protected ?string $provider;

  protected string $state;

  protected string $oAuthToken;

  protected string $oAuthVerifier;

  protected string $username;

  protected string $email;

  protected string $password;

  protected ?bool $rememberMe;

  protected ?string $continue;

  protected string $uri;

  protected array $meta;

  protected ?string $requestTokenUrl;

  protected ?string $accessTokenUrl;

  public function getProvider() : ?string
  {
    return $this->provider;
  }

  public function setProvider(string $value) : void
  {
    $this->provider = $value;
  }

  public function getState() : string
  {
    return $this->state;
  }

  public function setState(string $value) : void
  {
    $this->state = $value;
  }

  public function getOAuthToken() : string
  {
    return $this->oAuthToken;
  }

  public function setOAuthToken(string $value) : void
  {
    $this->oAuthToken = $value;
  }

  public function getOAuthVerifier() : string
  {
    return $this->oAuthVerifier;
  }

  public function setOAuthVerifier(string $value) : void
  {
    $this->oAuthVerifier = $value;
  }

  public function getUserName() : ?string
  {
    return $this->username;
  }

  public function setUserName(string $value) : void
  {
    $this->username = $value;
  }

  public function getRememberMe() : \bool
  {
    return $this->rememberMe;
  }

  public function setRememberMe($value) : void
  {
    $this->rememberMe = $value;
  }

  public function getContinue() : ?string
  {
    return $this->continue;
  }

  public function setContinue(string $value) : void
  {
    $this->continue = $value;
  }

  public function getUri() : string
  {
    return $this->uri;
  }

  public function setUri(string $value) : void
  {
    $this->uri = $value;
  }
  public function getMeta() : array
  {
    return $this->meta;
  }

  public function setMeta(array $values) : void
  {
    $this->meta = $values;
  }

  public function getPassword() : ?string
  {
    return $this->password;
  }

  public function setPassword(string $pw) : void
  {
    $this->password = $pw;
  }

  public function getEmail() : ?string
  {
    return $this->email;
  }

  public function setEmail(string $pw) : void
  {
    $this->email = $pw;
  }

  public function getAccessTokenUrl() : ?string
  {
    return $this->accessTokenUrl;
  }

  public function setAccessTokenUrl(string $pw) : void
  {
    $this->accessTokenUrl = $pw;
  }

  public function getRequestTokenUrl() : ?string
  {
    return $this->requestTokenUrl;
  }

  public function setRequestTokenUrl(string $pw) : void
  {
    $this->requestTokenUrl = $pw;
  }


  /*
  [DataMember(Order = 1)] public string provider { get; set; }
       [DataMember(Order = 2)] public string State { get; set; }
       [DataMember(Order = 3)] public string oauth_token { get; set; }
       [DataMember(Order = 4)] public string oauth_verifier { get; set; }
       [DataMember(Order = 5)] public string UserName { get; set; }
       [DataMember(Order = 6)] public string Password { get; set; }
       [DataMember(Order = 7)] public bool? RememberMe { get; set; }
       [DataMember(Order = 8)] public string Continue { get; set; }
       // Thise are used for digest auth
       [DataMember(Order = 9)] public string nonce { get; set; }
       [DataMember(Order = 10)] public string uri { get; set; }
       [DataMember(Order = 11)] public string response { get; set; }
       [DataMember(Order = 12)] public string qop { get; set; }
       [DataMember(Order = 13)] public string nc { get; set; }
       [DataMember(Order = 14)] public string cnonce { get; set; }
       [DataMember(Order = 15)] public Dictionary<string, string> Meta { get; set; }
       */
}
