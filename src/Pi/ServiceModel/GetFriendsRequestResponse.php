<?hh

namespace Pi\ServiceModel;

use Pi\Response;

class GetFriendsRequestResponse extends Response {
	
	protected $friends;

	public function getFriends()
	{
		return $this->friends;
	}

	public function setFriends($friends)
	{
		$this->friends = $friends;
	}
}