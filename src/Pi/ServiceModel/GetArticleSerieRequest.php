<?hh

namespace Pi\ServiceModel;

class GetArticleSerieRequest {

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
