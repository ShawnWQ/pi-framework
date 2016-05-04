<?hh

namespace Pi\ServiceModel;


use Pi\Response;



class FindArticleSerieResponse extends Response {
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