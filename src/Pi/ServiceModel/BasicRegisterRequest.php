<?hh

namespace Pi\ServiceModel;

class BasicRegisterRequest {

	protected $id;

	protected $firstName;

	protected $lastName;

	protected $displayName;

	protected $email;

	protected $password;

	protected string $city;

	<<Id>>
	public function id($value = null)
	{
		if($value === null) return $this->id;
		$this->id = $value;
	}

	<<String,Null>>
	public function firstName($value = null)
	{
		if($value === null) return $this->firstName;
		$this->firstName = $value;
	}

	<<String,Null>>
	public function lastName($value = null)
	{
		if($value === null) return $this->lastName;
		$this->lastName = $value;
	}

	<<String>>
	public function displayName($value = null)
	{
		if($value === null) return $this->displayName;
		$this->displayName = $value;
	}

	<<String>>
	public function email($value = null)
	{
		if($value === null) return $this->email;
		$this->email = $value;
	}

	<<String>>
	public function password($value = null)
	{
		if($value === null) return $this->password;
		$this->password = $value;
	}

	<<String>>
  	public function getCity() : string
	{
		return $this->city;
	}

	public function setCity(string $value) : void
	{
		$this->city = $value;
	}
}
