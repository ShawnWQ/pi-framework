<?hh
namespace SpotEvents\ServiceModel;

class CreateEventSubscription {

	protected \MongoId $eventId;

	protected \MongoId $ownerId;

	protected $price;

	protected $priceWithoutTaxes;

	protected $taxes;

	protected $description;

	protected Map $customFields;

	<<ObjectId>>
	public function eventId($value = null)
	{
		if($value === null) return $this->eventId;
		$this->eventId = $value;
	}

	<<ObjectId>>
	public function ownerId($value = null)
	{
		if($value === null) return $this->ownerId;
		$this->ownerId = $value;
	}

	<<Price>>
	public function price($value = null)
	{
		if($value=== null) return $this->price;
		$this->price = $value;
	}

	<<Decimal>>
	public function taxes($value = null)
	{
		if($value === null) return $this->taxes;
		$this->taxes = $value;
	}

	<<String>>
	public function description($value = null)
	{
		if($value === null) return $this->description;
		$this->description = $value;
	}

	<<Map>>
	public function customFields($value = null)
	{
		if($value === null) return $this->customFields;
		$this->customFields = $value;
	}
}