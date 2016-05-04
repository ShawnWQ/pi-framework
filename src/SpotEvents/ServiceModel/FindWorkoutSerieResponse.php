<?hh

namespace SpotEvents\ServiceModel;


use Pi\Response;



class FindWorkoutSerieResponse extends Response {
	protected $series;

	public function getSeries()
	{
		return $this->series;
	}

	public function setSeries($series)
	{
		$this->series = $series;
	}
}
