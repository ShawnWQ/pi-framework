<?hh

namespace Pi\ServiceModel;

class ApplicationChangeDomain {
	
	protected $appId;

	protected $newDomain;

	public function getAppId()
	{
		return $this->appId;
	}

	public function setAppId($value)
	{
		$this->appId = $value;
	}

	public function getNewDomain()
	{
		return $this->newDomain;
	}

	public function setNewDomain($value)
	{
		$this->newDomain = $value;
	}
}