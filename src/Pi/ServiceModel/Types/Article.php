<?hh

namespace Pi\ServiceModel\Types;

use Pi\ServiceModel\ArticleState;

/**
 * An article, such as a news article or piece of investigative report. Newspapers and magazines have articles of many different types and this is intended to cover them all
 */
<<Collection("article"),InheritanceType('Single'),DiscriminatorField('type'),DefaultDiscriminatorValue('article'),MultiTenant>>
class Article extends CreativeWork {

	public function __construct()
	{
		parent::__construct();
	}

	protected $appId;

	/**
	 * The actual body of the article.
	 */
	protected string $articleBody;

	/**
	 * The number of words in the text of the Article.
	 */
	protected int $wordCount;

	/**
	 * Articles may belong to one or more 'sections' in a magazine or newspaper, such as Sports, Lifestyle, etc.
	 */
	protected $articleSection;

	protected ?string $categoryPath;

	protected $category;

	protected $serie;

	protected ArticleState $state;

	const TYPE = 'Pi\ServiceModel\Types\Article';


	public function getAppId()
	{
		return $this->appId;
	}

	public function setAppId($id)
	{
		$this->appId = $id;
	}

	<<EmbedOne('Pi\ServiceModel\Types\ArticleCategoryEmbed')>>
	public function getCategory()
	{
		return $this->category;
	}

	public function setCategory($entity) : void
	{
		$this->category = $entity;
	}

	<<EmbedMany('Pi\ServiceModel\Types\ArticleSerieEmbed')>>
	public function getSerie()
	{
		return $this->serie;
	}

	public function setSerie($entity) : void
	{
		$this->serie = $entity;
	}

	<<String>>
	public function getCategoryPath() : ?string
	{
		return $this->categoryPath;
	}

	public function setCategoryPath(string $value) : void
	{
		$this->categoryPath = $value;
	}

	<<String>>
	public function getArticleBody()
	{
		return $this->articleBody;
	}

	public function setArticleBody(string $body)
	{
		$this->articleBody = $body;
	}

	<<String>>
	public function getWordCount()
	{
		return $this->wordCount;
	}

	public function setWordCount($counter)
	{
		$this->counter = $counter;
	}

	public function getArticleSection()
	{
		return $this->articleSection;
	}

	public function setArticleSection(string $section)
	{
		$this->articleSection = $section;
	}

	<<Int>>
	public function getState()
	{
		return $this->state;
	}

	public function setState($state)
	{
		$this->state = $state;
	}
}
