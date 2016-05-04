<?hh

namespace Pi\Auth\Interfaces;


interface IAuthRepository {

  public function loadUserAuth(IAuthSession $session, IAuthTokens $tokens) : void;

  public function saveUserAuth(IAuthSession $session) : void;

  public function getUserAuth(IAuthSession $session, IAuthTokens $tokens) : ?IUserAuth;

  public function getUserAuthDetails($userAuthId) : array;
  
  public function getUserAuthByUserName(string $userNameOrEmail) : IUserAuth;

  public function createOrMergeAuthSession(IAuthSession $session, IAuthTokens $tokens) : IUserAuthDetails;

  public function createUserAuth(IUserAuth $newUser, string $passwordHash) : IUserAuth;
}