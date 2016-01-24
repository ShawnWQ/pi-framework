<?hh

namespace Pi\ServiceModel;

class UserInfo {
	
	protected $displayName;

	protected $firstName;

	protected $lastName;

	protected \MongoId $id;

	public function getId()
	{
		return $this->id;
	}

	public function setId(\MongoId $id)
	{
		$this->id = $id;
	}

	public function getDisplayName()
	{
		return $this->displayName;
	}

	public function setDisplayName(string $value)
	{
		$this->displayName = $value;
	}

	public function getFirstName()
	{
		return $this->firstName;
	}

	public function setFirstName(string $value)
	{
		$this->firstName = $value;
	}

	public function getLastName()
	{
		return $this->lastName;
	}

	public function setLastName(string $value)
	{
		$this->lastName = $value;
	}
}