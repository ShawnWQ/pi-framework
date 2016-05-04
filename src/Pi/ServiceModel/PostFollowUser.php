<?hh

namespace Pi\ServiceModel;

class PostFollowUser {

	protected \MongoId $userId;

	<<ObjectId>>
	public function getUserId()
	{
		return $this->userId;
	}

	public function setUserId(\MongoId $id)
	{
		$this->userId = $id;
	}
}