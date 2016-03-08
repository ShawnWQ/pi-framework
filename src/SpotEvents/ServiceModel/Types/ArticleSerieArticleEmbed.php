<?hh

namespace Pi\ServiceModel\Types;


<<EmbedDocument>>
class ArticleSerieArticleEmbed {
	
	protected \MongoId $id;
	
	protected $image = 'http://lorempixel.com/325/100/';

	protected string $name;

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