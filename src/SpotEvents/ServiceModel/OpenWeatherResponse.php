<?hh

namespace SpotEvents\ServiceModel;

use Pi\Response;




class OpenWeatherResponse extends Response {
	/*
	*/
	public function __construct(
		protected string $main,
		protected string $description,
		protected $temp,
		protected $humidity,
		protected $tempMin,
		protected $tempMax,
		protected $seaLevel,
		protected $grndLevel,
		protected $windSpeed,
		protected $sunrise,
		protected $sunset
	)
	{

	}

	public function getMain() : string
	{
		return $this->main;
	}

	public function getDescription() : string
	{
		return $this->description;
	}

	public function getTempMin()
	{
		return $this->tempMin;
	}

	public function getTempMax()
	{
		return $this->tempMax;
	}

	public function getSeaLevel()
	{
		return $this->seaLevel;
	}

	public function getGrndLevel()
	{
		return $this->grndLevel;
	}

	public function getWindSpeed()
	{
		return $this->windSpeed;
	}

	public function getSunrise()
	{
		return $this->sunrise;
	}

	public function getSunset()
	{
		return $this->sunset;
	}
}