<?hh

namespace SpotEvents\ServiceModel;

class PostNutritionSerieRequest {

	protected string $description;

	protected $image = 'http://lorempixel.com/325/100/';

	protected string $name;

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
