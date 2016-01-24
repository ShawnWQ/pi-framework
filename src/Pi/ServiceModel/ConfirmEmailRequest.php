<?hh

namespace Pi\ServiceModel;

class ConfirmEmailRequest {
	
	protected $email;

	public function getEmail()
	{
		return $this->email;
	}

	public function setEmail($value)
	{
		$this->value = $value;
	}
}