<?hh

namespace Pi\Auth;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;

class AuthConfig implements IContainable {

	public function ioc(IContainer $ioc){}

	public function __construct(
			protected $authTokenTimeout = 20, 
			protected string $authTokenRestPath = '/authorize',
			protected string $tokenRestPath = '/token',
			protected string $loginRestPath = '/login',
			protected string $logoutRestPath = '/logout'
		)
	{

	}

	public function getAuthTokenTimeout() : string
	{
		return $this->authTokenTimeout;
	}

	public function getAuthTokenRestPath() : string
	{
		return $this->authTokenRestPath;
	}

	public function getTokenRestPath() : string
	{
		return $this->tokenRestPath;
	}

	public function getLoginRestPath() : string
	{
		return $this->loginRestPath;
	}

	public function getLogoutRestPath() : string
	{
		return $this->logoutRestPath;
	}
}