<?hh

namespace Warez\ServiceModel;

class PostSrtToVttRequest {

	protected string $filePath;

	<<String>>
	public function getFilePath()
	{
		return $this->filePath;
	}

	public function setFilePath(string $value)
	{
		$this->filePath = $value;
	}
}