<?hh

namespace Pi\ServiceModel;


use Pi\Extensions;
use Pi\Odm\Interfaces\IEntity;

class ArticleSerieDto implements \JsonSerializable, IEntity {

	protected \MongoId $id;
	
	protected string $description;

	protected $image = 'http://lorempixel.com/325/100/';

	protected string $name;

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

	public function jsonSerialize()
	{
		return Extensions::jsonSerialize($this);
	}

	  /**
   * @return mixed
   */
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

	/**
	* @param mixed $image
	*/
	public function setId($value)
	{
	  $this->id = $value;
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

	/**
	* @return mixed
	*/
	<<String,NotNull>>
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
}