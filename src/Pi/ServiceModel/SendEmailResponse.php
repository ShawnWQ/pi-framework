<?hh

namespace Pi\ServiceModel;




class SendEmailResponse {
	
	public function __construct(
	bool $success = true, ?string $errorMessage = null)
	{

	}

	public function getSuccess() : bool
	{
		return $this->success;
	}

	public function getErrorMessage() : ?string
	{
		return $this->errorMessage;
	}
}