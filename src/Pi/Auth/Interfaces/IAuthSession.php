<?hh

namespace Pi\Auth\Interfaces;

use Pi\Interfaces\IRequest;
use Pi\Interfaces\IService;

interface IAuthSession {

  public function getReferenceUrl() : string;

  public function getId() : string;

  public function setId(string $id) : void;

  public function getUserId() : \MongoId;

  public function setUserId(\MongoId $id) : void;

  public function getUserAuthName() : string;

  public function setUserAuthName(string $value)  : void;

  public function getUserName() : string;

  public function setUserName(string $value)  : void;

  public function getDisplayName() : string;

  public function setDisplayName(string $value)  : void;

  public function getFirstName() : string;

  public function getLastName() : string;

  public function getEmail() : string;

  public function setEmail(string $value)  : void;

  public function getCreatedAt() : \DateTime;

  public function getLastModified() : \DateTime;

  public function getRoles() : array;

  public function getPermissions() : array;

  public function isAuthenticated() : bool;

  public function getSequence() : string;

  public function getProviderOAuthAccess() : array;

  public function setProviderOAuthAccess(array $values) : void;

  public function addProviderOAuthAccess($value) : void;

  public function hasRole(string $role) : bool;

  public function hasPermission(string $permission) : bool;

  public function isAuthorized(string $provider) : bool;

  public function onRegistered(IRequest $request, IAuthSession $session, IService $service) : void;

  public function onAuthenticated(IService $authService, IAuthSession $session, IAuthTokens $tokens, $authInfo) : void;

  public function onLogout(IService $authService) : void;

  public function onCreated(IRequest $request) : void;

}
