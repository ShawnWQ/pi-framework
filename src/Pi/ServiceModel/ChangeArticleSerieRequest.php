<?hh

namespace Pi\ServiceModel;

class ChangeArticleSerieRequest {
	
	protected \MongoId $articleId;

	protected \MongoId $serieId;

	<<ObjectId>>
	public function getArticleId()
	{
		return $this->articleId;
	}

	public function setArticleId(\MongoId $id) : void
	{
		$this->articleId = $id;
	}

	<<ObjectId>>
	public function getSerieId()
	{
		return $this->serieId;
	}

	public function setSerieId(\MongoId $id) : void
	{
		$this->serieId = $id;
	}
}