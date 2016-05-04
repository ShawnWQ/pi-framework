<?hh

namespace SpotEvents\ServiceModel\Types;

use Pi\ServiceModel\Types\GeoCoordinates;




class RunCheckpoint {

	public function __construct(
		protected RunCheckpointType $type,
		protected GeoCoordinates $geo,
		protected ?string $obs = null
	)
	{

	}

	public function getRunCheckpoint() : RunCheckpointType
	{
		return $this->type;
	}

	public function setRunCheckpointType(RunCheckpointType $type) : void
	{
		$this->type = $type;
	}

	public function getGeoCoordinates() : GeoCoordinates
	{
		return $this->geo;
	}

	public function setGeoCoordinates(GeoCoordinates $geo) : void
	{
		$this->geo = $geo;
	}

	public function getObs() : ?string
	{
		return $this->obs;
	}

	public function setObs(string $value) : void
	{
		$this->obs = $value;
	}

}