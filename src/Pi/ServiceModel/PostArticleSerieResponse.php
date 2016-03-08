<?hh

namespace Pi\ServiceModel;

use Pi\Response;

class PostArticleSerieResponse extends Response {
	
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