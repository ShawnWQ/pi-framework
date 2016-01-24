<?hh

namespace Pi\VirtualPath;

use Pi\VirtualPath\Interfaces\IVirtualFile;
use Pi\VirtualPath\Interfaces\IVirtualDirectory;
use Pi\VirtualPath\Interfaces\IVirtualPathProvider;
use Pi\Interfaces\IPiHost;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;


abstract class AbstractVirtualPathProviderBase implements IVirtualPathProvider, IContainable {
	
	protected IVirtualDirectory  $rootDir;

	public function __construct(protected IPiHost $appHost)
	{
		if(is_null($appHost)) {
			throw new \Exception('appHost');
		}
	}

    public function ioc(IContainer $ioc)
    {
        
    }

	public function getAppHost() : IPiHost
	{
		return $this->appHost;
	}

	public function combineVirtualPath(string $basePath, string $relativePath) : string
    {
        return $basePath .  $this->getVirtualPathSeparator() .  $relativePath;
    }

    public function fileExists(string $fileName) : bool
    {
    	return $this->getFile($fileName) != null;
    }

    public function directoryExists(string $dirName) : bool
    {
    	return $this->getDirectory($dirName) != null;
    }

    public function getFile() : ?IVirtualFile
    {
    	return $this->rootDir->getFile($path);
    }

    public function getDirectory(string $path)
    {
    	return $this->rootDir->getDirectory($path);
    }

    public function getAllMatchingFiles(string $pattern, int $maxDepth = null)
    {
    	return $this->rootDir->getAllMatchingFiles($pattern, $maxDepth);
    }

    protected abstract function initialize();

    	/**
	 * Return the root directory from where the application is running
	 */
	public abstract function getRootDirectory() : IVirtualDirectory;

	public abstract function getVirtualPathSeparator() :  string;

	public abstract function getRealPathSeparator() : string;

}