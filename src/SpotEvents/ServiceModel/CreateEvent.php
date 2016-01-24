<?hh

namespace SpotEvents\ServiceModel;


class CreateEvent {

 	protected $duration;

 	protected $doorTime;

 	protected $endDate;

 	protected $title;

 	protected $excerpt;

 	protected $content;

 	protected $modalityId;

 	protected $cardGen;

 	protected ?array $tags;

 	protected string $image;

 	protected string $city;

 	protected $latitude;

 	protected $longitude;

 	protected $url;

 	<<String>>
	public function getUrl() : ?string
	{
		return $this->url;
	}

	public function setUrl(string $value) : void
	{
		$this->url = $value;
	}

	<<Collection>>
	public function getTags()
	{
		return $this->tags;
	}

	public function setTags($value)
	{
		$this->tags = $tags;
	}

 	<<Required,String>>
	public function title($value = null)
	{
		if($value === null) { return $this->title;}
		$this->title = $value;
	}

	<<Required,String>>
	public function image($value = null)
	{
		if($value === null) { return $this->image;}
		$this->image = $value;
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

	<<ObjectId>>
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

	<<String>>
	public function getLatitude()
	{
		return $this->latitude;
	}

	public function setLatitude($latitude)
	{
		$this->latitude = $latitude;
	}

	<<String>>
	public function getLongitude()
	{
		return $this->longitude;
	}

	public function setLongitude($longitude)
	{
		$this->longitude = $longitude;
	}
}
