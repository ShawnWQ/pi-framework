<?hh

namespace Pi\ServiceModel\Types;

use Pi\Odm\Interfaces\IEntity;
<<EmbeddedDocument,Entity>>
class GeoCoordinates extends StructuredValue implements \JsonSerializable, IEntity {

	protected $latitude;

	protected $longitude;

	protected $elevation;

	public function jsonSerialize()
	{
		return array(
			'latitude' => $this->latitude,
			'longitude' => $this->longitude,
			'elevation' => $this->elevation);
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
