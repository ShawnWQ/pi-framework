<?hh

namespace Pi\ServiceModel;

use Pi\Response;

class GetFriendStatusResponse extends Response {

	protected $isFriend;

	public function isFriend()
	{
		return $this->isFriend;
	}

	public function setIsFriend($value)
	{
		$this->isFriend = $value;
	}
}