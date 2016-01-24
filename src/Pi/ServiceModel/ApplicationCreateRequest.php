<?hh

namespace Pi\ServiceModel;

class ApplicationCreateRequest {
	
	public function __construct(
		public ?string $name = null,
		protected ?string $description = null,
		protected ?string $domain = null,
		protected ?\MongoId $ownerId = null)
	{
		
	}

	public function getName() : string
	{
		return $this->name;
	}

	public function getDescription() : string
	{
		return $this->description;
	}

	public function getOwnerId() : ?\MongoId
	{
		return $this->ownerId;
	}

	public function getDomain() : string
	{
		return $this->domain;
	}
}