<?hh

namespace Pi\ServiceModel;

class GetAlbunsRequest {
	
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