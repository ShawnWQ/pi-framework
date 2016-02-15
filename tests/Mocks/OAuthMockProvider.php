<?hh

namespace Mocks;

use Pi\Auth\OAuthProvider;
use Pi\Auth\Authenticate;
use Pi\Interfaces\IService;
use Pi\Auth\Interfaces\IAuthSession;
use Pi\Auth\Interfaces\IAuthTokens;

class OAuthMockProvider extends OAuthProvider {

  public function authenticate(IService $authService, IAuthSession $session, Authenticate $request)
  {

  }

  public function isAuthorized(IAuthSession $session, IAuthTokens $tokens, Authenticate $request = null)
  {

  }

}
