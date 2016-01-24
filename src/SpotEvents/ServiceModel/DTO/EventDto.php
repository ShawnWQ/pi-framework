<?hh

namespace SpotEvents\ServiceModel\DTO;
use Pi\Odm\Interfaces\IEntity;

<<Entity,Collection("Events")>>
class EventDto implements \JsonSerializable, IEntity{

	protected $viewsCounter;

	protected string $url;

	protected $likesCount;

	protected $commentsCount;

	protected $title;

	protected $excerpt;

	protected $content;

	protected $doorTime;

	protected $duration;

	protected $endDate;

	protected $subscriptionId;

	protected $id;

	protected string $cardGen;

	protected string $thumbnailSrc;

	protected $author;

    protected $subscriptionAmount;

    protected $tags;

    protected string $image;

    protected $latitude;

    protected $longitude;

    <<String>>
	public function getImage()
	{
		return $this->image;
	}

	public function setImage(string $uri)
	{
		$this->image = $uri;
	}

	<<Collection>>
	public function getTags()
	{
		return $this->tags;
	}

	public function setTags($value)
	{
		$this->tags = $value;
	}

	<<EmbedOne('Pi\ServiceModel\Types\Author')>>
	public function author($value = null)
	{
		if($value === null) return $this->author;
		$this->author = $value;
	}
	public function getAuthor()
	{
		return $this->author;
	}

	public function setAuthor($value)
	{
		$this->author = $value;
	}

		public function jsonSerialize()
	{
		$arr = get_object_vars($this);

		$arr['id'] = (string)$arr['id'];
		$arr['subscriptionId'] = (string)$arr['subscriptionId'];
		return $arr;
	}

	<<Id>>
	public function id($value = null)
	{
		if($value === null) return $this->id;
		$this->id = $value;
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

    <<Int>>
    public function getSubscriptionAmount()
    {
        return $this->subscriptionAmount;
    }

	<<String>>
	public function getThumbnailSrc()
	{
		return $this->thumbnailSrc;
	}

	public function setThumbnailSrc(string $uri)
	{
		$this->thumbnailSrc = $uri;
	}

	<<ObjectId>>
	public function getSubscriptionId()
	{
		return $this->subscriptionId;
	}

	public function setSubscriptionId(\MongoId $id)
	{
		$this->subscriptionId = $id;
	}

	<<String>>
	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle(string $value)
	{
		$this->title = $value;
	}

	<<String>>
	public function getContent()
	{
		return $this->content;
	}

	<<String>>
	public function getExcerpt()
	{
		return $this->excerpt;
	}

	public function setExcerpt(string $value)
	{
		$this->excerpt = $value;
	}

	<<DateTime>>
	public function getDoorTime()
	{
		return $this->doorTime;
	}

	public function setDoorTime(\DateTime $value)
	{
		$this->doorTime = $value;
	}

	public function getDuration()
	{
		return $this->duration;
	}

	public function setDuration($value)
	{
		$this->duration = $value;
	}

	<<DateTime>>
	public function getEndDate()
	{
		return $this->endDate;
	}

	public function setEndDate(\DateTime $value)
	{
		$this->endDate = $value;
	}

	<<Int>>
	public function getCommentsCount()
	{
		return $this->commentsCount;
	}

	public function setCommentsCount(int $value)
	{
		$this->commentsCount = $value;
	}

	<<Int>>
	public function getLikesCount()
	{
		return $this->likesCount;
	}

	public function setLikesCount(int $value)
	{
		$this->likesCount = $value;
	}

	<<Int>>
	public function getViewsCounter()
	{
		return $this->viewsCounter;
	}

	public function setViewsCounter($value)
	{
		$this->viewsCounter = $value;
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

	<<String>>
	public function getUrl()
	{
		return $this->url;
	}

	public function setUrl($value)
	{
		$this->url = $value;
	}
}
