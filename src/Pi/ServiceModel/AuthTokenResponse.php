<?hh

namespace Pi\ServiceModel;

class AuthTokenResponse {

	public function __construct(protected string $token)
	{

	}

	public function getCode()
	{
		return $this->token;
	}
}
