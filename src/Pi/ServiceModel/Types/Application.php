<?hh

namespace Pi\ServiceModel\Types;
use Pi\Odm\Interfaces\IEntity;

class Application implements IEntity {

	protected $id;

	/**
	 * The secret token
	 */
	protected $name;

	protected $description;

	protected $ownerId;
 
	protected string $returnUrl;

	protected $apiVersion;

	protected $contactEmail;

	protected array $platforms;

	protected string $deauthorizeUrl;

	/**
	 * The secret token
	 */
	protected string $clientToken;

	protected bool $clientOauthLogin;

	protected string $domain;

	public function id($value = null)
	{
		if(is_null($value)) return $this->id;
		$this->id = $value;
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
	public function getDomain() : string
	{
		return $this->domain;
	}


	public function setDomain(string $domain) : void
	{
		$this->domain = $domain;
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

	<<String>>
	public function getOwnerId() : ?\MongoId
	{
		return $this->ownerId;
	}

	public function setOwnerId(\MongoId $ownerId) : void
	{
		$this->ownerId = $ownerId;
	}
}