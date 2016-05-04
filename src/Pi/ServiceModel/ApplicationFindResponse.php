<?hh

namespace Pi\ServiceModel;
use Pi\Response;

class ApplicationFindResponse extends Response {
	
	protected $applications;

	public function setApplications($applications)	
	{
		$this->applications = $applications;
	}

	public function getApplications()
	{
		return $this->applications;
	}
}