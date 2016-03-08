<?hh

namespace Pi\Auth;

use Pi\Auth\Interfaces\IAuthSession;
use Pi\Auth\Interfaces\IAuthTokens;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IService;

class AuthUserSession implements IAuthSession, \JsonSerializable {

  protected $referenceUrl = '';

  protected $id;

  protected $userId;

  protected $userName = '';

  protected $displayName = '';

  protected $firstName = '';

  protected $lastName = '';

  protected $email = '';

  protected $primaryEmai;

  /**
   * @var Pi\Auth\Interfaces\IAuthTokens OAuth tokens
   */
  protected $providerOAuthAccess = array();

  protected $createdAt;

  protected $lastModified;

  protected $roles = array();

  protected $permissions = array();

  protected $sequence;

  protected $isAuthenticated = false;

  protected string $userAuthName = '';

  protected ?string $facebookUserId;

  protected ?string $facebookUsername;

  protected ?string $facebookEmail;

  protected ?string $facebookFirstName;

  protected ?string $facebookLastName;

  protected ?string $facebookDisplayName;

  public function jsonSerialize()
  {
    $vars = get_object_vars($this);
    if(count($vars['providerOAuthAccess']) > 0) {
      $acccess = array();
      foreach ($vars['providerOAuthAccess'] as $provider) {
        $acccess[] = $provider->jsonSerialize();
      }
      $vars['providerOAuthAccess'] = $acccess;
    }
    return $vars;
  }

  public function getReferenceUrl() : string
  {
    return $this->referenceUrl;
  }

  public function getId() : string
  {
    return $this->id;
  }

  public function setId(string $id) : void
  {
    $this->id = $id;
  }

  public function getUserId()
  {
    return $this->userId;
  }

  public function setUserId(\MongoId $id) : void
  {
    $this->userId = $id;
  }

  public function getUserAuthName() : ?string
  {
    return $this->userAuthName;
  }

  public function setUserAuthName(string $value)  : void
  {
    $this->userAuthName = $value;
  }

  public function getUserName() : string
  {
    return $this->userName;
  }

  public function setUserName(string $value)  : void
  {
    $this->userName = $value;
  }

  public function getDisplayName() : string
  {
    return $this->displayName;
  }

  public function setDisplayName(string $value)  : void
  {
    $this->displayName = $value;
  }

  public function getFirstName() : string
  {
    return $this->firstName;
  }

  public function setFirstName(string $value)
  {
    $this->firstName = $value;
  }

  public function getLastName() : string
  {
    return $this->lastName;
  }

  public function setLastName(string $value)
  {
    $this->lastName = $value;
  }

  public function getEmail() : string
  {
    return $this->email;
  }

  public function setEmail(string $value)  : void
  {
    $this->email = $value;
  }

  public function getPrimaryEmail() : ?string
  {
    return $this->primaryEmail;
  }

  public function setPrimaryEmail(string $value)  : void
  {
    $this->primaryEmail = $value;
  }

  public function getProviderOAuthAccess() : array
  {
    return $this->providerOAuthAccess;
  }

  public function setProviderOAuthAccess(array $values) : void
  {
    $this->providerOAuthAccess = $values;
  }

  public function addProviderOAuthAccess($value) : void
  {
    $this->providerOAuthAccess[] = $value;
  }

  public function getCreatedAt() : \DateTime
  {
    return $this->createdAt;
  }

  public function setCreatedAt(\DateTime $value)
  {
    $this->createdAt = $value;
  }

  public function getLastModified() : ?\DateTime
  {
    return $this->lastModified;
  }

  public function setLastModified(\DateTime $value)
  {
    $this->lastModified = $value;
  }

  public function getRoles() : array
  {
    return $this->roles;
  }

  public function setRoles(array $values) : void
  {
    $this->roles = $values;
  }

  public function getPermissions() : array
  {
    return $this->permissions;
  }

  public function setPermissions(array $values) : void
  {
    $this->permissions = $values;
  }

  public function getSequence() : string
  {
    return $this->sequence;
  }

  public function isAuthenticated() : bool
  {
    return $this->isAuthenticated;
  }

  public function setIsAuthenticated(bool $value) : void
  {
    $this->isAuthenticated = $value;
  }

  public function hasRole(string $role) : bool
  {
    return array_key_exists($role, $this->roles);
  }

  public function hasPermission(string $permission) : bool
  {
    return array_key_exists($permission, $this->permissions);
  }

  <<String>>
  public function getFacebookUserId() : ?string
  {
    return $this->facebookUserId;
  }

  public function setFacebookUserId(string $value)  : void
  {
    $this->facebookUserId = $value;
  }

  <<String>>
  public function getFacebookEmail() : ?string
  {
    return $this->facebookEmail;
  }

  public function setFacebookEmail(string $value)  : void
  {
    $this->facebookEmail = $value;
  }

  <<String>>
  public function getFacebookDisplayName() : ?string
  {
    return $this->facebookDisplayName;
  }

  public function setFacebookDisplayName(string $value)  : void
  {
    $this->displayName = $value;
  }

  <<String>>
  public function getFacebookFirstName() : ?string
  {
    return $this->facebookFirstName;
  }

  public function setFacebookFirstName(string $value)  : void
  {
    $this->facebookFirstName = $value;
  }

  <<String>>
  public function getFacebookLastName() : ?string
  {
    return $this->facebookLastName;
  }

  public function setFacebookLastName(string $value)  : void
  {
    $this->facebookLastName = $value;
  }

  public function isAuthorized(string $provider) : bool
  {
    if(count($this->providerOAuthAccess) == 0)
      return false;
return false;
    //$provider = AuthService::getAuthProvider($this->providerOAuthAccess[0]->getProvider());
    //return $provider->isAuthorized($this, $this->providerOAuthAccess[0]);
  }

  public function onRegistered(IRequest $request, IAuthSession $session, IService $service) : void
  {

  }

  public function onAuthenticated(IService $authService, IAuthSession $session, IAuthTokens $tokens, $authInfo) : void
  {

  }

  public function onLogout(IService $authService) : void
  {

  }

  public function onCreated(IRequest $request) : void
  {

  }
}
