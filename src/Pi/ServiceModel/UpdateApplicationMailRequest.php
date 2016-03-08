<?hh

namespace Pi\ServiceModel;

class UpdateApplicationMailRequest {
	

	protected string $footer;

	protected string $header;

	protected string $fromName;

	protected string $fromEmail;

	protected \MongoId $appId;

	<<AppId>>
	public function getAppId()
	{
		return $this->appId;
	}

	public function setAppId(\MongoId $id)
	{
		$this->appId = $id;
	}

	public function getFooter()
	{
		return $this->footer;
	}

	public function setFooter(string $value)
	{
		$this->footer = $value;
	}

	public function getHeader()
	{
		return $this->header;
	}

	public function setHeader(string $header)
	{
		$this->header = $header;
	}

	public function getFromEmail()
	{
		return $this->fromEmail;
	}

	public function setFromEmail(string $value)
	{
		$this->fromEmail = $value;
	}

	public function getFromName()
	{
		return $this->fromName;
	}

	public function setFromName(string $value) : void
	{
		$this->fromName = $value;
	}
}