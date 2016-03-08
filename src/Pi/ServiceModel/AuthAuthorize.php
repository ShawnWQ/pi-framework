<?hh

namespace Pi\ServiceModel;

class AuthAuthorize {

	public function __construct(protected $responseType = null,
		protected $clientId = null,
		protected $redirectUri = null,
		protected $scope = null)
	{

	}

	public function getResponse()
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
}
