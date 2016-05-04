<?hh

namespace Warez\ServiceModel;

class FacebookBotLogin {
	
	protected string $email;

	protected string $password;

	public function getEmail()
	{
		return $this->email;
	}

	public function setEmail(string $value)
	{
		$this->email = $value;
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function setPassword(string $value)
	{
		$this->password = $value;
	}
}