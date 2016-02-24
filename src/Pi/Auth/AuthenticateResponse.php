<?hh

namespace Pi\Auth;

use Pi\Interfaces\HasSessionIdInterface;




class AuthenticateResponse implements HasSessionIdInterface {
	
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

	public function getSessionId() : string
	{
		return $this->sessionId;
	}

	public function getRefferUrl()
	{
		return $this->refferUrl;
	}
}
