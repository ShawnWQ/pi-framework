<?hh

namespace Pi\ServiceModel;
use Pi\Odm\Interfaces\IEntity;

<<Collection('Application')>>
class ApplicationDto implements \JsonSerializable, IEntity {
	protected $id;

	protected $name;

	protected $description;

	protected $ownerId;

	protected $domain;

	public function jsonSerialize()
	{
		$vars = get_object_vars($this);
		$vars['id'] = (string)$vars['id'];
		return $vars;
	}

	<<String>>
	public function getDomain() : string
	{
		return $this->domain;
	}

	public function setDomain(string $domain) : void
	{
		$this->domain = $domain;
	}

	<<Id>>
	public function getId()
	{
		return $this->id;
	}
	public function setId($value)
	{
		$this->id = $value;
	}

	<<String>>
	public function getName() : string
	{
		return $this->name;
	}

	public function setName(string $name) : void
	{
		$this->name = $name;
	}

	<<String>>
	public function getDescription() : string
	{
		return $this->description;
	}

	public function setDescription(string $description) : void
	{
		$this->description = $description;
	}

	<<ObjectId>>
	public function getOwnerId() : ?\MongoId
	{
		return $this->ownerId;
	}

	public function setOwnerId(\MongoId $ownerId) : void
	{
		$this->ownerId = $ownerId;
	}
}
