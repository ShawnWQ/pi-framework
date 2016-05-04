<?hh

namespace Pi\ServiceModel;

class PostUnfollowUser {

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