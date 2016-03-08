<?hh

namespace Pi\ServiceModel;

class AuthAuthorizeResponse {

	public function __construct(protected string $code)
	{

	}

	public function getCode()
	{
		return $this->code;
	}
}
