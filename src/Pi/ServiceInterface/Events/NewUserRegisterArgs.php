<?hh

namespace Pi\ServiceInterface\Events;

use Pi\EventArgs;
use Pi\ServiceModel\UserDto;

class NewUserRegisterArgs extends EventArgs {

	public function __construct(protected ?UserDto $user = null)
	{

	}

	public function getUserId() : \MongoId
	{
		return $this->user->id();
	}

	public function getUser() : UserDto
	{
		if(is_null($this->user)) {
			throw new \Exception();
		}
		return $this->user;
	}
}
