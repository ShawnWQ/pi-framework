<?hh

namespace SpotEvents\ServiceModel;

class UpdateEventRequest {
	protected $duration;

 	protected $doorTime;

 	protected $endDate;

 	protected $title;

 	protected $excerpt;

 	protected $content;

 	protected $modalityId;

 	protected $cardGen;

 	protected ?array $tags;

 	protected string $thumbnailSrc;

 	protected \MongoId $id;

 	<<Required,String>>
	public function id($value = null)
	{
		if($value === null) { return $this->id;}
		$this->id = $value;
	}

 	<<Required,String>>
	public function title($value = null)
	{
		if($value === null) { return $this->title;}
		$this->title = $value;
	}

	<<Required,String>>
	public function thumbnailSrc($value = null)
	{
		if($value === null) { return $this->thumbnailSrc;}
		$this->thumbnailSrc = $value;
	}

	<<String>>
	public function getCardGen()
	{
		return $this->cardGen;
	}

	public function setCardGen(string $uri)
	{
		$this->cardGen = $uri;
	}

	<<Required,String>>
	public function excerpt($value = null)
	{
		if($value === null) { return $this->excerpt;}
		$this->excerpt = $value;
	}

	<<Required,String>>
	public function content($value = null)
	{
		if($value === null) { return $this->content;}
		$this->content = $value;
	}

	<<Id>>
	public function modalityId($value = null)
	{
		if($value === null) { return $this->modalityId;}
		$this->modalityId = $value;
	}

 	<<Required,DateTime>>
	public function doorTime($value = null)
	{
		if($value === null) { return $this->doorTime;}
		$this->doorTime = $value;
	}

	<<Required,Timestamp>>
	public function duration($value = null)
	{
		if($value == null) return $this->duration;
		$this->duration = $value;
	}

	<<Required,DateTime>>
	public function endDate($value = null)
	{
		if($value == null) return $this->endDate;
		$this->endDate = $value;
	}
}