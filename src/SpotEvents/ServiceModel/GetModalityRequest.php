<?hh

namespace SpotEvents\ServiceModel;


class GetModalityRequest  {

	protected \MongoId $id;

	public function getId() : \MongoId
	{
		return $this->id;
	}

	public function setId(\MongoId $id) : void
	{
		$this->id = $id;
	}
}