<?hh

namespace Pi\ServiceModel;

class PostApplicationMessage {
	
	protected string $name;

	protected string $description;


	<<String>>
	public function getName()
	{
		return $this->name;
	}

	public function setName(string $value) : void
	{
		$this->name = $value;
	}

	<<String>>
	public function getDescription()
	{
		return $this->description;
	}

	public function setDescription(string $value) : void
	{
		$this->description = $value;
	}
}