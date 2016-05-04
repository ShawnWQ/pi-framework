<?hh

namespace Pi\ServiceModel;




class PostWorkPublishDate {
	
	protected \MongoId $id;

	protected $date;

	<<ObjectId>>
	public function getId()
	{
		return $this->id;
	}

	public function setId(\MongoId $id)
	{
		$this->id = $id;
	}

	<<DateTime>>
	public function getDate()
	{
		return $this->date;
	}

	public function setDate(\DateTime $date)
	{
		$this->date = $date;;
	}
}