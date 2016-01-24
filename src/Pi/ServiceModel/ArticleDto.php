<?hh

namespace Pi\ServiceModel;

use Pi\Odm\Interfaces\IEntity;

<<Collection("article")>>
class ArticleDto implements IEntity, \JsonSerializable {

  protected \MongoID $id;

  /**
   * An alias for the item.
   */
  protected string $alternateName;

  protected string $description;

  protected $image;

  protected string $name;

  protected string $url;

  protected string $urlName;

  /**
   * The party holding the legal copyright to the CreativeWork.
   */
  protected $copyrightHolder;

  /**
   * The year during which the claimed copyright for the CreativeWork was first asserted.
   */
  protected $copyrightYear;

  /**
   * The creator/author of this CreativeWork. This is the same as the Author property for CreativeWork.
   */
  protected $author;

  /**
   * The date on which the CreativeWork was created.
   * @var \DateTime
   */
  protected $dateCreated;

  /**
   * The date on which the CreativeWork was most recently modified.
   * @var \DateTime
   */
  protected $dateModified;

  /**
   * Date of first broadcast/publication.
   * @var \Datetime
   */
  protected $datePublished;

  /**
   * Headline of the article.
   */
  protected $headline;

  /**
   * The language of the content or performance or used in an action. Please use one of the language codes from the IETF BCP 47 standard. Supersedes language.
   */
  protected $inLanguage;

  /**
   * Keywords or tags used to describe this content. Multiple entries in a keywords list are typically delimited by commas.
   */
  protected $keywords;

  /**
   * A thumbnail image relevant to the Thing.
   */
  protected $thumbnailUrl;

  /**
	 * The actual body of the article.
	 */
	protected string $articleBody;

	/**
	 * Articles may belong to one or more 'sections' in a magazine or newspaper, such as Sports, Lifestyle, etc.
	 */
	protected $articleSection;

  protected ?string $categoryPath;

  protected $category;

  protected $serie;

  protected $viewsCounter = 0;

  protected ArticleState $state;

  protected ?string $refferName;

  protected ?string $refferUrl;

  protected ?string $refferImage;

  <<EmbedOne('Pi\ServiceModel\Types\ArticleCategoryEmbed')>>
  public function getCategory()
  {
    return $this->category;
  }

  public function setCategory($entity) : void
  {
    $this->category = $entity;
  }


  <<EmbedOne('Pi\ServiceModel\Types\ArticleSerieEmbed')>>
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

  public function jsonSerialize()
  {
    $vars = get_object_vars($this);
    $vars['id'] = (string)$vars['id'];
    return $vars;
  }

  <<Id>>
  public function id($value = null)
  {
    if(is_null($value)) return $this->id;
    $this->id = $value;
  }

  public function getId()
  {
    return $this->id;
  }

  public function setId(\MongoId $id)
  {
    $this->id = $id;
  }

  <<String>>
  public function getAlternateName()
  {
    return $this->alternateName;
  }

  public function setAlternateName(string $name)
  {
    $this->alternateName = $name;
  }

  <<String>>
  public function getDescription()
  {
    return $this->description;
  }

  public function setDescription(string $value)
  {
    $this->description = $value;
  }

  <<String>>
  public function getUrl()
  {
    return $this->url;
  }

  public function setUrl(string $value)
  {
    $this->url = $value;
  }

  <<String>>
  public function getUrlName()
  {
    return $this->urlName;
  }

  public function setUrlName(string $value)
  {
    $this->urlName = $value;
  }

  /**
   * @return mixed
   */
   <<String>>
  public function getImage()
  {
      return $this->image;
  }

  /**
   * @param mixed $image
   */
  public function setImage($image)
  {
      $this->image = $image;
  }

  <<String>>
  public function getName()
  {
    return $this->name;
  }

  public function setName(string $value)
  {
    $this->name = $value;
  }

  /**
   * @return mixed
   */
  public function getCopyrightHolder()
  {
      return $this->copyrightHolder;
  }

  /**
   * @param mixed $copyrightHolder
   */
  public function setCopyrightHolder($copyrightHolder)
  {
      $this->copyrightHolder = $copyrightHolder;
  }

  /**
   * @return mixed
   */
  public function getCopyrightYear()
  {
      return $this->copyrightYear;
  }

  /**
   * @param mixed $copyrightYear
   */
  public function setCopyrightYear($copyrightYear)
  {
      $this->copyrightYear = $copyrightYear;
  }

  /**
   * @return mixed
   */
  <<EmbedOne('Pi\ServiceModel\Types\Author')>>
  public function getAuthor()
  {
      return $this->author;
  }

  /**
   * @param mixed $author
   */
  public function setAuthor($author)
  {
      $this->author = $author;
  }

  /**
   * @return \DateTime
   */
   <<DateTime>>
  public function getDateCreated()
  {
      return $this->dateCreated;
  }

  /**
   * @param \DateTime $dateCreated
   */
  public function setDateCreated($dateCreated)
  {
      $this->dateCreated = $dateCreated;
  }

  /**
   * @return \DateTime
   */
   <<DateTime>>
  public function getDateModified()
  {
      return $this->dateModified;
  }

  /**
   * @param \DateTime $dateModified
   */
  public function setDateModified($dateModified)
  {
      $this->dateModified = $dateModified;
  }

  /**
   * @return \Datetime
   */
   <<DateTime>>
  public function getDatePublished()
  {
      return $this->datePublished;
  }

  /**
   * @param \Datetime $datePublished
   */
  public function setDatePublished($datePublished)
  {
      $this->datePublished = $datePublished;
  }

  /**
   * @return mixed
   */
  <<String>>
  public function getHeadline()
  {
      return $this->headline;
  }

  /**
   * @param mixed $headline
   */
  public function setHeadline($headline)
  {
      $this->headline = $headline;
  }

  /**
   * @return mixed
   */
  public function getInLanguage()
  {
      return $this->inLanguage;
  }

  /**
   * @param mixed $inLanguage
   */
  public function setInLanguage($inLanguage)
  {
      $this->inLanguage = $inLanguage;
  }

  /**
   * @return mixed
   */
  <<Collection>>
  public function getKeywords()
  {
      return $this->keywords;
  }

  /**
   * @param mixed $keywords
   */
  public function setKeywords($keywords)
  {
      $this->keywords = $keywords;
  }

  /**
   * @return mixed
   */
  <<String>>
  public function getThumbnailUrl()
  {
      return $this->thumbnailUrl;
  }

  /**
   * @param mixed $thumbnailUrl
   */
  public function setThumbnailUrl($thumbnailUrl)
  {
      $this->thumbnailUrl = $thumbnailUrl;
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
	public function getArticleSection()
	{
		return $this->articleSection;
	}

	public function setArticleSection(string $section)
	{
		$this->articleSection = $section;
	}

  <<Int>>
	public function getViewsCounter()
	{
		return $this->viewsCounter;
	}

	public function setViewsCounter($value)
	{
		$this->viewsCounter = $value;
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

  <<String>>
  public function getRefferName() : ?string
  {
    return $this->refferName;
  }

  public function setRefferName(string $value) : void
  {
    $this->refferName = $value;
  }

  <<String>>
  public function getRefferUrl() : ?string
  {
    return $this->refferUrl;
  }

  public function setRefferUrl(string $value) : void
  {
    $this->refferUrl = $value;
  }

  <<String>>
  public function getRefferImage() : ?string
  {
    return $this->refferImage;
  }

  public function setRefferImage(string $value) : void
  {
    $this->refferImage = $value;
  }
}
