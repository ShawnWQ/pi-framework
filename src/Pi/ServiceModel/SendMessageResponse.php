<?hh

namespace Pi\ServiceModel;

use Pi\Response;

class SendMessageResponse extends Response {

	protected $message;

	public function getMessage()
	{
		return $this->message;
	}

	public function setMessage($value)
	{
		$this->message = $value;
	}

}