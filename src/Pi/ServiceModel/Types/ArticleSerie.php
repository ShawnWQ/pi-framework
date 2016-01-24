<?hh

namespace Pi\ServiceModel\Types;

class ArticleSerie extends Thing {
	
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