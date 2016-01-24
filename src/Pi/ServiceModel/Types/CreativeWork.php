<?hh

namespace Pi\ServiceModel\Types;

/**
 * The most generic kind of creative work, including books, movies, photographs, software programs, etc.
 */
class CreativeWork extends Thing {
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
	protected $thumbnailUrl = 'http://fitting.pt/uploads/image-default/thing';

  protected ?string $refferName;

  protected ?string $refferUrl;

  protected ?string $refferImage;

  public function __construct()
  {
    $this->refferName = null;
    $this->refferUrl = null;
    $this->refferImage = null;
  }

  /**
   * @return mixed
   */
	<<String>>
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
	<<String>>
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
	 <<String>>
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
  <<String,NotNull>>
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
