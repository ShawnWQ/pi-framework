<?hh

namespace SpotEvents\ServiceModel\Types;
use Pi\Odm\Interfaces\IEntity;
use Pi\ServiceModel\Types\Thing;
use SpotEvents\ServiceInterface\Interfaces\IHasCardGen;

<<Entity,Collection("Events")>>
class EventEntity extends Thing implements IEntity, IHasCardGen{

	protected $viewsCounter;

	protected $likesCount;

	protected $commentsCount;
	protected string $title;

	protected string $excerpt;

	protected string $content;

    protected $subscriptionAmount;

	/**
	 * @description A person or organization attending the event
	 */
	protected $attend;

	/**
	 * @description The time admission will commence.
	 * @var \DateTime
	 */
	protected $doorTime;

	/**
	 * @description The duration of the item (movie, audio recording, event, etc.)
	 */
	protected $duration;

	/**
	 * @description The end date and time of the item
	 */
	protected $endDate;

	/**
	 * @description An eventStatus of an event represents its status; particularly useful when an event is cancelled or rescheduled.
	 * @var Pi\Event\Domain\EventStatusType
	 */
	protected $eventStatus;

	/**
	 * @description The location of the event, organization or actio
	 */
	protected $latitude;

	protected $longitude;

	/**
	 * @description An organizer of an Event.
	 */
	protected $organizer;

	/**
	 * @descriptio  A performer at the event—for example, a presenter, musician, musical group or actor.
	 */
	protected $performer;

	/**
	 * @description Used in conjunction with eventStatus for rescheduled or cancelled events. This property contains the previously scheduled start date. For rescheduled events, the startDate property should be used for the newly scheduled start date. In the (rare) case of an event that has been postponed and rescheduled multiple times, this field may be repeated.
	 */
	protected $previousStartDate;

	/**
	 * @descriptio The start date and time of the item
	 * @var \DateTime
	 */
	protected $startDate;

	/**
	 * @description An Event that is part of this event. For example, a conference event includes many presentations, each of which is a subEvent of the conference.
	 */
	protected $subEvent;

	/**
	 * @description An event that this event is a part of. For example, a collection of individual music performances might each have a music festival as their superEvent.
	 */
	protected $superEvent;

	/**
	 * @description The typical expected age range,
	 */
	protected $typicalAge;

	/**
	 * @var \MongoId
	 */
	protected $id;

	/**
	 * @var \MongoId
	 */
	protected $modalityId;

	protected $subscriptionId;

	protected string $cardGen;

	protected string $thumbnailSrc;

	protected string $image;

	protected $author;

	protected $tags;

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
	public function getAuthor()
	{
		return $this->author;
	}

	public function setAuthor($value)
	{
		$this->author = $value;
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

	<<String>>
	public function getImage()
	{
		return $this->image;
	}

	public function setImage(string $uri)
	{
		$this->image = $uri;
	}

	<<Id>>
	public function id($value = null)
	{
		if($value === null) { return $this->id;}
		$this->id = $value;
	}

	<<String>>
	public function getCardGen() : string
	{
		return $this->cardGen;
	}

	public function setCardGen(string $uri)
	{
		$this->cardGen = $uri;
	}

	<<ObjectId>>
	public function subscriptionId($value = null)
	{
		if($value === null) { return $this->subscriptionId;}
		$this->subscriptionId = $value;
	}

	<<ObjectId>>
	public function modalityId($value = null)
	{
		if($value === null) { return $this->modalityId;}
		$this->modalityId = $value;
	}


	public function attend($value = null)
	{
		if($value === null) { return $this->attend;}
		$this->attend = $value;
	}

	<<DateTime>>
	public function doorTime($value = null)
	{
		if($value === null) { return $this->doorTime;}
		$this->doorTime = $value;
	}

	<<Timestamp>>
	public function duration($value = null)
	{
		if($value == null) return $this->duration;
		$this->duration = $value;
	}

	<<DateTime>>
	public function endDate($value = null)
	{
		if($value == null) return $this->endDate;
		$this->endDate = $value;
	}

	<<String>>
	public function title($value = null)
	{
		if($value == null) return $this->title;
		$this->title = $value;
	}

	<<String>>
	public function content($value = null)
	{
		if($value == null) return $this->content;
		$this->content = $value;
	}

	<<String>>
	public function excerpt($value = null)
	{
		if($value == null) return $this->excerpt;
		$this->excerpt = $value;
	}

  <<Int>>
  public function subscriptionAmount($value = null)
  {
      if($value == null) return $this->subscriptionAmount;
      $this->subscriptionAmount = $value;
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
