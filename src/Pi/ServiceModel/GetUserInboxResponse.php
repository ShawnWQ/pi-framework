<?hh

namespace Pi\ServiceModel;

use Pi\Response;

class GetUserInboxResponse extends Response {

	protected $messages;

	protected UserDto $otherUser;

	public function getOtherUser()
	{
		return $this->otherUser;
	}

	public function setOtherUser(UserDto $dto)
	{
		$this->otherUser = $dto;
	}

	public function getMessages()
	{
		return $this->messages;
	}

	public function setMessages($messages)
	{
		$this->messages = $messages;
	}
}
