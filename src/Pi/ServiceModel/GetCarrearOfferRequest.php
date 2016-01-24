<?hh

namespace Pi\ServiceModel;

class GetCarrearOfferRequest {

	protected \MongoId $id;

	public function getId()
	{
		return $this->id;
	}

	public function setId(\MongoId $id)
	{
		$this->id = $id;
	}
}