<?hh

namespace Pi\ServiceModel;

class PostWorkReffer {
	
	protected ?string $refferName;

  	protected ?string $refferUrl;

  	protected ?string $refferImage;

  	protected \MongoId $id;

  	<<ObjectId>>
  	public function getId()
  	{
  		return $this->id;
  	}

  	public function setId(\MongoId $value) : void
  	{
  		$this->id = $value;
  	}

  	<<String>>
	public function getRefferName() : ?string
	{
		return $this->refferName;
	}

	public function setRefferName(string $value) : void
	{
		$this->refferName = $value;
	}

	<<String>>
	public function getRefferUrl() : ?string
	{
		return $this->refferUrl;
	}

	public function setRefferUrl(string $value) : void
	{
		$this->refferUrl = $value;
	}

	<<String>>
	public function getRefferImage() : ?string
	{
		return $this->refferImage;
	}

	public function setRefferImage(string $value) : void
	{
		$this->refferImage = $value;
	}
}