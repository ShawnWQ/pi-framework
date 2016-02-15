<?hh

namespace Pi\Auth;

class AuthenticateResponse {
	
	public function __construct(

		protected $userId,
		protected string $userName,
		protected string $displayName,
		protected $sessionId,
		protected string $refferUrl)
	{

	}

	public function getUserId()
	{
		return $this->userId;
	}

	public function getUserName() : string
	{
		return $this->userName;
	}

	public function getDisplayName() : string
	{
		return $this->displayName;
	}

	public function getSessionId()
	{
		return $this->sessionId;
	}

	public function getRefferUrl()
	{
		return $this->refferUrl;
	}
}
