<?hh

namespace Pi\ServiceModel;

class PostPlaceRequest {

	/**
	 * An alias for the item.
	 */
	protected string $alternateName;

	protected string $description;

	protected $image;

	protected string $name;

	protected string $url;

  protected $address;

	protected $aggregateRating;

	protected \MongoId $containedIn;

	protected string $faxNumber;

	protected string $globalLocationNumber;

	protected $review;

	protected string $telephone;

	protected array $openingHours;

	protected $types;

	protected $latitude;

	protected $longitude;

	protected $elevation;

  <<String>>
  public function getAlternateName()
  {
    return $this->alternateName;
  }

  public function setAlternateName(string $name)
  {
    $this->alternateName = $name;
  }

  <<String>>
  public function getDescription()
  {
    return $this->description;
  }

  public function setDescription(string $value)
  {
    $this->description = $value;
  }

  /**
   * @return mixed
   */
   <<String>>
  public function getImage()
  {
      return $this->image;
  }

  /**
   * @param mixed $image
   */
  public function setImage($image)
  {
      $this->image = $image;
  }

  <<String>>
  public function getName()
  {
    return $this->name;
  }

  public function setName(string $value)
  {
    $this->name = $value;
  }

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

	<<String>>
	public function getLatitude()
	{
		return $this->latitude;
	}

	public function setLatitude($value)
	{
		$this->latitude = $value;
	}

	<<String>>
	public function getLongitude()
	{
		return $this->longitude;
	}

	public function setLongitude($value)
	{
		$this->longitude = $value;
	}

	<<String>>
	public function getElevation()
	{
		return $this->elevation;
	}

	public function setElevation($value)
	{
		$this->elevation = $value;
	}
}
