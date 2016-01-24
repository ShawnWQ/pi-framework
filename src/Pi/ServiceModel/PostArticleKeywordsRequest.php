<?hh

namespace Pi\ServiceModel;

class PostArticleKeywordsRequest {
	
	public function __construct(
		protected mixed $keywords,
		protected \MongoId $id)
	{

	}

	public function getKeywords()
	{
		return $this->keywords;
	}

	public function getId()
	{
		return $this->id;
	}
}