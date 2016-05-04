<?hh

namespace Pi\Host;

class Cookie {
	
	public function __construct(

		protected string $name,
		protected string $value,
		protected ?\DateTime $expiration,
		protected string ?$domain
	)
	{

	}

	public function getName() : string
	{
		return $this->name;
	}

	public function getValue() : string
	{
		return $this->value;
	}

	public function getExpiration() : ?DateTime
	{
		return $this->expiration;
	}

	public function getDomain() : ?string
	{
		return $this->domain;
	}
}