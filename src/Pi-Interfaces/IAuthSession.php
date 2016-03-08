<?hh

namespace Pi\Interfaces;

interface IAuthSession {

  public function referrerUrl() : string;

  public function id();
  
  public function userAuthId();

  public function userAuthName() : string;

  public function userName() : string;

  public function displayName() : string;

  public function firstName() : string;

  public function lastName() : string;

  public function email() : email;

  /**
   * A array of IAuthTokens
   */
  public function providerOAuthAccess();

  public function createdAt();

  public function lastModified();

  public function roles() : array;

  public function permissions() : array;

  public function isAuthenticated() : bool;
  // public function sequence(); used for digest authentication replay protection

  public function hasRole($role) : bool;

  public function hasPermission($permissions) : bool;

  public function isAuthorized($provider) : bool;

  public function onRegistered(IRequest $httpReq, IAuthSession $session, IServiceBase $service) : void;
  /**
   * @param $authInfo Dictionary<string, string>
   */
  public function onAuthenticated(IServiceBase $authService, IAuthSession $session, IAuthTokens $tokens,  $authInfo) : void;

  public function onLogout(IServiceBase $authService) : void;

  public function onCreated(IRequest $httpReq) : void;
}
