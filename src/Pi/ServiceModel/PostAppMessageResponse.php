<?hh

namespace Pi\ServiceModel;
use Pi\Response;

class PostAppMessageResponse extends Response {
	
	protected AppMessageDto $message;

	public function getMessage()
	{
		return $this->appMessage;
	}

	public function setMessage(AppMessageDto $dto)
	{
		$this->message = $dto;
	}
}