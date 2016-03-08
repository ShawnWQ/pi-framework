<?hh

namespace Pi\ServiceModel;

use Pi\Response;

class GetFollowersResponse extends Response {
	
	protected $users;

	public function getFollowing()
	{
		return $this->users;
	}

	public function setFollowing($friends)
	{
		$this->users = $friends;
	}
}