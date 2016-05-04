<?hh

namespace Pi\Auth\Interfaces;

interface IUserAuthRepository extends IAuthRepository {

  public function createUserAuth(IUserAuth $newUser, string $password) : IUserAuth;

  public function updateUserAuth(IUserAuth $existing, IUserAuth $newUser, string $password) : IUserAuth;
}
