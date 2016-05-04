<?hh

namespace Pi\ServiceModel;

use Pi\Response;

class ApplicationGetResponse extends Response {
	
	protected $application;
	
	public function setApplication(ApplicationDto $dto)
	{
		$this->application = $dto;
	}

	public function getApplication()
	{
		return $this->application;
	}
}