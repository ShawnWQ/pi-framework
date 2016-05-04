<?hh

namespace Pi\VirtualPath;

use Pi\VirtualPath\Interfaces\IVirtualFile;
use Pi\VirtualPath\Interfaces\IVirtualDirectory;
use Pi\VirtualPath\Interfaces\IVirtualPathProvider;


class FileSystemVirtualFile extends AbstractVirtualFileBase {
	
	protected \SplFileObject $phpFile;

	public function __construct(
		IVirtualPath $virtualPathProvider,
		IVirtualPathDirectory $directory,
		protected \SplFileObject $phpFile
		)
	{
		parent::__construct($virtualPathProvider, $directory);
	}

	public function getName()  : string
	{
		return $this->phpFile->getFilename();
	}

	public function getRealPath() : string
	{
		return $this->phpFile->getBasename();
	}
}