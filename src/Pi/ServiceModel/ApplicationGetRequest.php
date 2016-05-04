<?hh

namespace Pi\ServiceModel;

class ApplicationGetRequest {
	
	protected \MongoId $appId;

	public function setAppId($value)
	{
		$this->appId = $value;
	}

	public function getAppId()
	{
		return $this->appId;
	}
}