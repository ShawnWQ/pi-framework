<?hh

namespace Pi\ServiceModel;

class AuthUserAccount {
	
	public function __construct(protected \MongoId $userId, protected string $name, protected $roles = array())
	{

	}

	public function userId() : \MongoId
	{
		return $this->userId;
	}

	public function name() : string
	{
		return $this->name;
	}

	public function roles() : array
	{
		return $this->roles;
	}
}