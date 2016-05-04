<?hh

namespace SpotEvents\ServiceModel;




class PostEventCategory {
	
	protected \MongoId $id;

	protected string $categoryId;

	<<ObjectId>>
	public function getId()
	{
		return $this->id;
	}

	public function setId(\MongoId $id)
	{
		$this->id = $id;
	}

	public function getCategoryId()
	{
		return $this->categoryId;
	}

	public function setCategoryId(string $id)
	{
		$this->categoryId = $id;
	}
}