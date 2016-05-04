<?hh

namespace Pi\ServiceInterface\Events;

use Pi\EventArgs;
use Pi\ServiceModel\UserDto;

class NewFriendshipArgs extends EventArgs {

	public function __construct(protected ?UserDto $user = null, protected ?UserDto $friend = null)
	{

	}

	public function getUserId() : \MongoId
	{
		return $this->user->id();
	}

	public function getUser() : ?UserDto
	{
		return $this->user;
	}

	public function getFriendId() : \MongoId
	{
		return $this->friend->id();
	}

	public function getFriend() : ?UserDto
	{
		return $this->friend;
	}
}