<?hh

namespace Pi\Interfaces;
use Pi\NotImplementedException;
use Pi\Interfaces\IContainable;
use Pi\ServiceModel\AuthUserAccount;

interface IRequest extends IContainable {

  public function getTemporarySessionId() : string;

  public function getPermanentSessionId() : string;

  public function setAppId($value);

  public function appId();

  public function items() : array;

  public function resolve($type);

  public function response();

  /**
   * The name of the service operation being called. Ie: PutUserInfoRequest
   */
  public function operationName() : string;

  /**
   * The verbe/HttpMethod or Action for this requset
   */
  public function verbe() : string;

  public function preferences(): IRequestPreferences;

  public function dto();

  public function getRawBody();

  public function remoteIp() : string;

  public function inputStream();

  public function contentLong();

  public function files();

  public function serverPort() : int;

  public function serverName() : string;

  /**
   * The name of referrer, null if not available
   * @return string|null
   */
  public function urlReferrer();

  public function setDto($dto);

  /**
   * Helper to convert the request method to a Applyto enum
   * This is usefull to execute filters and others objects that
   * depend on ApplyTo enum
   */
  public function httpMethodAsApplyTo();

  public function setResponse($response);

  public function setUserAccount(AuthUserAccount $dto);

  public function getUserId();

  public function userAccount();

  public function author();

  public function tryResolve(string $name);

  public function isAuthenticated() : bool;
}
