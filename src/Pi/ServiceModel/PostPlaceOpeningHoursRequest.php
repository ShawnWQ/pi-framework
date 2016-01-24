<?hh

namespace Pi\ServiceModel;

use Pi\ServiceModel\Types\StructuredValue,
	Pi\Interfaces\IOpeningHoursModel;

class PostPlaceOpeningHoursRequest extends StructuredValue implements IOpeningHoursModel {

	protected \MongoId $id;
	/**
	 * The closing hour of the place or service on the given day(s) of the week.
	 */
	protected $closes;

	/**
	 * The day of the week for which these opening hours are valid.
	 */
	protected $dayOfWeek;

	/**
	 * The opening hour of the place or service on the given day(s) of the week.
	 */
	protected $opens;

	/**
	 * The date when the item becomes valid
	 */
	protected \DateTime $validFrom;

	/**
	 * The end of the validity of offer, price specification, or opening hours data.
	 */
	protected \DateTime $validThrough;

	<<Id>>
	public function getId()
	{
		return $this->id;
	}

	public function setId(\MongoId $id)
	{
		$this->id = $id;
	}

	<<String>>
	public function getCloses()
	{
		return $this->closes;
	}

	public function setCloses($value)
	{
		$this->closes = $value;
	}

	<<Int>>
	public function getDayOfWeek()
	{
		return $this->dayOfWeek;
	}

	public function setDayOfWeek($day)
	{
		$this->dayOfWeek = $day;
	}

	<<String>>
	public function getOpens()
	{
		return $this->opens;
	}

	public function setOpens($value)
	{
		$this->opens = $value;
	}

	<<DateTime>>
	public function getValidFrom()
	{
		return $this->validFrom;
	}

	public function setValidFrom(\DateTime $value)
	{
		$this->validFrom = $value;
	}

	<<DateTime>>
	public function getValidThrough()
	{
		return $this->validThrough;
	}

	public function setValidThrough(\DateTime $value)
	{
		$this->validThrough = $value;
	}
}