<?hh

namespace Pi\Auth\Interfaces;

interface IAuthRepository {

  public function loadUserAuth(IAuthSession $session, IAuthTokens $tokens) : void;

  public function saveUserAuth(IAuthSession $session) : void;

  public function getUserAuth(IAuthSession $session, IAuthTokens $tokens) : IUserAuth;

  public function getUserAuthDetails($userAuthId) : array;
  
  public function getUserAuthByUserName(string $userNameOrEmail) : IUserAuth;

}
