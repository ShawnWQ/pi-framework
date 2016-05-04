<?hh

namespace Pi\ServiceModel\Types;

use Pi\Odm\Interfaces\IEntity;

class ArticleSerieArticleEmbed implements \JsonSerializable, IEntity {
	
	protected \MongoId $id;

	protected string $name;

	public function jsonSerialize()
	{
		$vars = get_object_vars($this);
		return $vars;
	}

	<<Id>>
	public function getId()
	{
		return $this->id;
	}

	public function setId(\MongoId $id)
	{
		$this->id = $id;
	}

	<<String>>
	public function getName() : string
	{
		return $this->name;
	}

	public function setName(string $value) : void
	{
		$this->name = $value;
	}
}