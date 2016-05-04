<?hh

namespace Pi\VirtualPath;

use Pi\VirtualPath\Interfaces\IVirtualFile;
use Pi\VirtualPath\Interfaces\IVirtualDirectory;
use Pi\VirtualPath\Interfaces\IVirtualPathProvider;


abstract class AbstractVirtualDirectoryBase implements IVirtualDirectory {
	
	protected \DateTime $lastModified;

	protected bool $isRoot = false;


	public function __construct(
		protected IVirtualPathProvider $pathProvider,
		protected ?IVirtualDirectory $parent = null,
		protected $directory
		)
	{
		$this->lastModified = new \DateTime('now');

	}

	public function getLastModified() : ?DateTime
	{
		return $this->lastModified;
	}

	public function gerParentDirectory() : ?IVirtualDirectory
	{
		return $this->parent;
	}

	public function getFile(string $path) : ?IVirtualFile
	{
		$tokens = VirtualPathExtensions::tokenizeVirtualPath($path);
		return $this->getFileDeep($tokens);
	}

	public function getFileDeep(array &$tokens) : ?IVirtualFile
	{
		if(count($tokens) == 0)
			return null;

		$token = array_pop($tokens);
		
		if(count($tokens) == 0)
			return $this->getFileFromPhpDirectoryOrDefault($token);

		$dir = $this->getDirectoryFromPhpDirectoryOrDefault($token);

		return !is_null($dir)
			? $dir->getFileDeep($tokens)
			: null;
	}

	public function getDirectory(string $path) : ?IVirtualDirectory
	{
		$tokens = VirtualPathExtensions::tokenizeVirtualPath($path);
		return $this->getDirectoryDeep($tokens);
	}

	public function getDirectoryDeep(Set<string> &$tokens) : ?IVirtualDirectory
	{
		if(count($tokens) == 0)
			return null;

		$token = array_pop($tokens);
		$dir = $this->getDirectoryFromPhpDirectoryOrDefault($token);
		if($dir === null)
			return null;

		return count($tokens === 0)
			? $dir
			: $dir->getDirectoryDeep($tokens);
	}

	public function getAllMatchingFiles(string $pattern, int $maxDepth = null)
	{
		if(is_null($maxDepth))
			$maxDepth = PHP_INT_MAX;

		$files = $this->getMatchinFilesFromDirectory($pattern);
		foreach($this->getDirectories() as $childDir) {
			$matching = $childDir->getAllMatchingFiles($pattern, $maxDepth - 1);
			$files = array_merge($files, $matching);
		}

		return $files;
	}


	public function getVirtualPath() : string
	{
		return $this->getVirtualPathToRoot();
	}

	public function getIsDirectory() : bool
	{

	}

	public function getIsRoot() : bool
	{

	}

	public function getFiles(bool $recursive = false, int $skip = 0, int $take = null) : array
	{

	}

	public function getDirectories(bool $recursive = true, int $skip = 0, int $take = null) : array
	{

	}

	public function getName() : string
	{

	}

	//protected abstract function getFileFromDirectory(string $fileName) : ?IVirtualFile;

	protected abstract function getDirectoryFromPhpDirectoryOrDefault() : ?IVirtualDirectory;

	protected abstract function getFileFromPhpDirectoryOrDefault(strinf $fileName) : ?IVirtualDirectory;
	
	protected abstract function getMatchingFilesInDir(string $pattern) : ?array;

	protected function getVirtualPathToRoot()
	{
		if($this->isRoot) {
			return $this->getPathProvider()->getVirtualPathSeparator();
		}
	}


}