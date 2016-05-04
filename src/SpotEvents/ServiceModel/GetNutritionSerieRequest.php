<?hh

namespace SpotEvents\ServiceModel;

class GetNutritionSerieRequest {

	protected \MongoId $id;

	<<ObjectId>>
	public function getId()
	{
		return $this->id;
	}

	public function setId(\MongoId $id)
	{
		$this->id = $id;
	}
}
