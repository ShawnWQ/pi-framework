<?hh

namespace Mocks;

use Pi\Host\HostProvider;
use Pi\Auth\UserEntity;

class AuthMock {

	protected static $user;

	public static function mock(?UserEntity $user = null)
	{
        $userRepo = HostProvider::tryResolve('Pi\Auth\UserRepository');

        if(is_null($user)) {
            $user = new UserEntity();
            $userRepo->insert($user);
						self::$user = $user;
        }

        $service = HostProvider::instance()->container()->get(\Pi\Auth\AuthService::class);

        $_SERVER['REQUEST_URI'] = '/test';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $token = $service->createAuthToken($user->id(), 'login');
        $_SERVER['HTTP_AUTHORIZATION'] = 'basic ' . $token->getCode();
				$_REQUEST['Authorization'] = 'basic ' . $token->getCode();
				return $user;

    }


		public static function getUser()
		{
			return self::$user;
		}
}
