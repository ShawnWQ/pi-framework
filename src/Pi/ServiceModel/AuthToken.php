<?hh

namespace Pi\ServiceModel;

class AuthToken implements \JsonSerializable {

	public function __construct(protected $responseType,
		protected $clientId,
		protected ?string $redirectUri = null,
		protected $scope = null,
		protected ?string $code = null)
	{

	}

	protected \DateTime $createdAt;

	protected int $expiresIn;

	public function jsonSerialize()
	{
		return get_object_vars($this);
	}

	public function setCode(string $value)
	{
		$this->code = $value;
	}

	public function getResponseType()
	{
		return $this->responseType;
	}

	public function getClientId()
	{
		return $this->clientId;
	}

	public function getRedirectUri()
	{
		return $this->redirectUri;
	}

	public function getScope()
	{
		return $this->scope;
	}

	public function getCode()
	{
		return $this->code;
	}
}
