<?hh

namespace Pi\VirtualPath;

use Pi\VirtualPath\Interfaces\IVirtualFile;
use Pi\VirtualPath\Interfaces\IVirtualDirectory;
use Pi\VirtualPath\Interfaces\IVirtualPathProvider;

class FileSystemVirtualDirectory extends AbstractVirtualDirectoryBase {

	public function __construct(
		IVirtualPathProvider $pathProvider,
		?IVirtualDirectory $parentDirectory = null,
		protected $dir
		)
	{
		parent::__construct($pathProvider, $parentDirectory, $dir);
	}

	protected function getDirectoryFromPhpDirectoryOrDefault() : ?IVirtualDirectory
	{
		return null;
	}


	protected function getMatchinFilesFromDirectory(string $path)
	{

	}

	protected function getAscendors(string $fileName, ?string $extension = null) : ?array
	{
		$files = scandir($fileName);
		return $files;	
	}

	protected function getFilesInt(string $fileName, bool $onlyFiles = true, bool $symlinks = true, ?string $extension = null) : ?array
	{

		if(is_null($extension)) {
			$files = array_filter(scandir($fileName), function($current) use($onlyFiles, $fileName, $symlinks, $extension) {
				
				return $onlyFiles && $symlinks
					? !is_dir($fileName . $current)
					: $onlyFiles
						? $current[0] !== '.'
						: true;
			});
			return $files;
		}
		//}
		$iterator = new \GlobIterator($fileName . '/*.', \FilesystemIterator::KEY_AS_FILENAME);
		$array = iterator_to_array($iterator);
		return $array;
		
	}

	protected function getDirectoriesInt(string $dirName) : ?array
	{

	}

	protected function getDirectoryFromPhpDirectory()
	{

	}

	protected function getFileFromPhpDirectoryOrDefault(strinf $fileName) : ?IVirtualDirectory
	{
		
	}

	protected function getFileFromDirectory(string $fileName) : ?IVirtualFile
	{

	}
	
	protected function getMatchingFilesInDir(string $pattern) : ?array
	{
		$filesInt = $this->getFilesInt($pattern);
		if(count($filesInt) == 0) 
			return null;

		$files = array();
		foreach($filesInt as $f) {
			$files[] = new FileSystemVirtualFile($this->pathProvider, $this, $f);
		}

		return $files;
	}

	protected function getFileFromPhpDirectory(string $name) : ?IVirtualFile
	{
		$files = $this->getFilesInt($name);
		return (!is_null($files) && count($files > 0))
			? new FileSystemVirtualFile($this->pathProvider, $this, $files[0])
			: null;
	}
}