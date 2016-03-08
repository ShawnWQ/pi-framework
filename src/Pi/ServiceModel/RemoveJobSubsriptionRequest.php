<?hh

namespace Pi\ServiceModel;

class RemoveJobSubsriptionRequest {

	protected \MongoId $id;

	public function getId()
	{
		return $this->id;
	}

	public function setId(\MongoId $value)
	{
		$this->id = $value;
	}
}
