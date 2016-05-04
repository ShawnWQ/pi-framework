<?hh

namespace Pi\ServiceModel;

use Pi\Response;




class GetUserResponse extends Response {
	
	protected $user;

	public function getUser()
	{
		return $this->user;
	}

	public function setUser($user)
	{
		$this->user = $user;
	}
}