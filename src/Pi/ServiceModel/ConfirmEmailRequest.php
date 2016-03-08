<?hh

namespace Pi\ServiceModel;

class ConfirmEmailRequest {

	protected \MongoId $id;
		protected ?string $token;
	public function __construct(
		
	)
	{

	}

	public function getToken(): ?string
	{
		return $this->token;
	}

	public function setToken(string $token)
	{
		return $this->token;
	}

	<<Id,ObjectId>>
	public function getId() 
	{
		return $this->id;
	}

	public function setId($value)
	{
		$this->id = $value;
	}
}