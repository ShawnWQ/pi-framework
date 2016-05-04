<?hh

namespace Pi\ServiceModel;




class PostWorkState {
	
	protected \MongoId $id;

	protected $state;

	<<ObjectId>>
	public function getId()
	{
		return $this->id;
	}

	public function setId(\MongoId $id)
	{
		$this->id = $id;
	}

	public function getState()
	{
		return $this->state;
	}

	public function setState($state)
	{
		$this->state= $state;
	}
}