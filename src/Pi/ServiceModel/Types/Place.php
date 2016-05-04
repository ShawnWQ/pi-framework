<?hh

namespace Pi\ServiceModel\Types;

use Pi\Odm\Interfaces\IEntity;


/**
 * Entities that have a somewhat fixed, physical extension.
 */
<<Entity,Collection('place'),MultiTenant>>
class Place extends Thing {

	protected $address;

	protected $aggregateRating;

	protected \MongoId $containedIn;

	protected string $faxNumber;

	protected string $globalLocationNumber;

	protected GeoCoordinates $geo;

	protected $review;

	protected string $telephone;

	protected $openingHours;

	protected $types;

	protected $author;

	<<Collection>>
	public function getTypes()
	{
		return $this->places;
	}

	public function setTypes($values)
	{
		$this->types = $values;
	}

	<<String>>
	public function getAddress()
	{
		return $this->address;
	}

	public function setAddress(string $value)
	{
		$this->address = $value;
	}

	<<String>>
	public function getAggregateRating()
	{
		return $this->aggregateRating;
	}

	public function setAggregateRating($value)
	{
		$this->aggregateRating = $value;
	}

	<<ObjectId>>
	public function getContainedIn()
	{
		return $this->containedIn;
	}

	public function setContainedIn(\MongoId $value)
	{
		$this->containedIn = $value;
	}

	<<String>>
	public function getFaxNumber()
	{
		return $this->faxNumber;
	}

	public function setFaxNumber(string $value) : void
	{
		$this->faxNumber = $value;
	}

	<<String>>
	public function getGlobalLocationNumber()
	{
		return $this->globalLocationNumber;
	}

	public function setGlobalLocationNumber(string $value)
	{
		$this->globalLocationNumber = $value;
	}

	<<EmbedOne('Pi\ServiceModel\Types\GeoCoordinates')>>
	public function getGeo()
	{
		return $this->geo;
	}

	public function setGeo($values)
	{
		$this->geo = $values;
	}

	public function getReview()
	{
		return $this->review;
	}

	public function setReview($values)
	{
		$this->review = $values;
	}

	public function getTelephone()
	{
		return $this->telephone;
	}

	public function setTelephone(string $value) : void
	{
		$this->telephone = $value;
	}

	<<EmbedMany('Pi\ServiceModel\Types\OpeningHoursSpecification')>>
	public function getOpeningHours()
	{
		return $this->openingHours;
	}

	public function setOpeningHours($value)
	{
		$this->openingHours = $value;
	}

	/**
	 * @return mixed
   	 */
	<<EmbedOne('Pi\ServiceModel\Types\Author')>>
	public function getAuthor()
	{
	  return $this->author;
	}

	/**
	* @param mixed $author
	*/
	public function setAuthor($author)
	{
	  $this->author = $author;
	}
}
