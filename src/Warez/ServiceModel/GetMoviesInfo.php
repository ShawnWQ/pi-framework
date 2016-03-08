<?hh

namespace Warez\ServiceModel;

class GetMoviesInfo {

	protected string $dirPath;

	public function getDirPath() : string
	{
		return $this->dirPath;
	}

	public function setDirPath(string $dirPath) : void
	{
		$this->dirPath = $dirPath;
	}
}