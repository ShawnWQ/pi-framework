<?hh

namespace Pi\ServiceModel\Types;
use Pi\Odm\Interfaces\IEntity;


class Thing implements IEntity, \JsonSerializable {

	protected $id;

	/**
	 * An alias for the item.
	 */
	protected string $alternateName;

	protected string $description;

	protected $image = 'http://lorempixel.com/325/100/';

	protected string $name;

	protected string $url;

  protected string $urlName;

	protected $viewsCounter = 0;

	public function jsonSerialize()
	{
		return get_object_vars($this);
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

	<<Int>>
	public function getViewsCounter()
	{
		return $this->viewsCounter;
	}

	public function setViewsCounter($value)
	{
		$this->viewsCounter = $value;
	}

}
