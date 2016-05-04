<?hh

namespace Pi\ServiceModel;


class GetApplicationMailRequest {

	public \MongoId $id;

	public function getId()
	{
		return $this->id;
	}

	public function setId(\MongoId $id)
	{
		$this->id = $id;
	}
}