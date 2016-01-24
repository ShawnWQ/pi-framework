<?hh

namespace SpotEvents\ServiceModel;

use Pi\Response;

class PostNutritionSerieResponse extends Response {

	protected $serie;

	public function setSerie($serie)
	{
		$this->serie = $serie;
	}

	public function getSerie()
	{
		return $this->serie;
	}
}
