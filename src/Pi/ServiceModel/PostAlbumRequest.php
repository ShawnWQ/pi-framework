<?hh

namespace Pi\ServiceModel;

class PostAlbumRequest {

	protected $title;

	protected $type;

	protected ?\MongoId $userId;

	<<String>>
	public function getType()
	{
		return $this->type;
	}

	public function setType($type)
	{
		$this->type = $type;
	}

	<<String>>
	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle(string $text)
	{
		$this->title = $text;
	}

	<<ObjectId,Null>>
	public function getUserId() : ?\MongoId
	{
		return $this->userId;
	}

	public function setUserId(\MongoId $id) : void
	{
		$this->userId = $id;
	}
}
