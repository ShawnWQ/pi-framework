<?hh

namespace Pi\ServiceModel;
use Pi\Response;

class ApplicationCreateResponse extends Response {

	public function __construct(protected ApplicationDto $application)
	{

	}

	public function getApplication()
	{
		return $this->application;
	}
}
