<?hh

namespace Pi\VirtualPath;

use Pi\Interfaces\IPiHost;
use Pi\VirtualPath\Interfaces\IVirtualFile;
use Pi\VirtualPath\Interfaces\IVirtualDirectory;
use Pi\VirtualPath\Interfaces\IVirtualPathProvider;


class FileSystemVirtualPathProvider extends AbstractVirtualPathProviderBase {

	protected $rootDirPhp;

	public function __construct(
		IPiHost $host,
		string $rootPath)
	{
		parent::__construct($host);
		if(empty($rootPath)) {
			throw new \Exception('rootPath');
		}
		$this->rootDirPhp = dirname($rootPath);
		$this->initialize();
	}

	public function getRootDirectory() : IVirtualDirectory
	{
		return $this->rootDir;
	}

	public function getVirtualPathSeparator() :  string
	{
		return '/';
	}

	public function getRealPathSeparator() : string
	{
		return '/';
	}
	
	protected function initialize() : void
	{
		if(is_null($this->rootDirPhp)) {
			$dirInfo = array('name' => dirname($this->appHost->config()->webHostPhysicalPath()));
		}
		$dirInfo = array();

		$this->rootDir = new FileSystemVirtualDirectory($this, null, $dirInfo);
	}
}