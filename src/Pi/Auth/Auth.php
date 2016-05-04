<?hh

namespace Pi\Auth;

class Auth {

	protected $id;

	protected $token;

	public function id($value = null)
	{
		if($value === null) return $this->id;
		$this->id = $value;
	}

	public function token($value = null)
	{
		if($value === null) return $this->token;
		$this->token = $value;
	}
}
