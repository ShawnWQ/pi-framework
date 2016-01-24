<?hh

namespace Pi\ServiceModel;

class GetFriendStatusRequest {
	
	protected \MongoId $userId;

	<<ObjectId>>
	public function getUserId()
	{
		return $this->userId;
	}

	public function setUserId(\MongoId $value)
	{
		$this->userId = $value;
	}
}