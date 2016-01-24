<?hh

namespace Pi\ServiceModel;

class GetUserInboxRequest {

	protected \MongoId $fromId;

	protected \MongoId $toId;

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