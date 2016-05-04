<?hh

namespace Pi\Auth\Interfaces;

use Pi\Interfaces\IService;
use Pi\Auth\Authenticate;

interface IAuthProvider {

  public function getOAuthRealm() : string;

  public function getProvider();

  public function getCallbackUrl() : string;

  public function logout(IService $service, Authenticate $request);

  public function authenticate(IService $authService, IAuthSession $session, Authenticate $request);

  public function isAuthorized(IAuthSession $session, IAuthTokens $tokens, Authenticate $request = null);
}
