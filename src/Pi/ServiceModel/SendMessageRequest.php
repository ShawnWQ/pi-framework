<?hh

namespace Pi\ServiceModel;

class SendMessageRequest {

	protected \MongoId $fromId;

	protected \MongoId $toId;

	protected string $message;

	<<String>>
	public function getMessage()
	{
		return $this->message;
	}

	public function setMessage(string $message)
	{
		$this->message = $message;
	}

	<<ObjectId>>
	public function getFromId()
	{
		return $this->fromId;
	}

	public function setFromId(\MongoId $id)
	{
		$this->fromId = $id;
	}

	<<ObjectId>>
	public function getToId()
	{
		return $this->toId;
	}

	public function setToId(\MongoId $id)
	{
		$this->toId = $id;
	}
	
}