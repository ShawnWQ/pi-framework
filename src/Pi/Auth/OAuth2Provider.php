<?hh

namespace Pi\Auth;

use Pi\Interfaces\IService;
use Pi\Auth\Interfaces\IAuthSession;
use Pi\Auth\Interfaces\IAuthTokens;


class OAuth2Provider extends OAuthProvider {

  

  public function loadUserOAuthProvider(IAuthSession $session, IOAuthTokens $tokens)
  {

  }
}
