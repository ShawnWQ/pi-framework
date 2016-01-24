<?hh

namespace SpotEvents\ServiceModel\Types;

use Pi\ServiceModel\Types\Thing;

<<Collection('workout-serie')>>
class WorkoutSerie extends Thing {

	protected array $articles;

	<<EmbedMany('Pi\ServiceModel\Types\ArticleSerieArticleEmbed')>>
	public function getArticles()
	{
		return $this->articles;
	}

	public function setArticles(array $values)
	{
		$this->articles = $values;
	}
}
