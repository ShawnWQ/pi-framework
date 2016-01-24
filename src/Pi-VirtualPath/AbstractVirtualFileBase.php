<?hh

namespace Pi\VirtualPath;

use Pi\VirtualPath\Interfaces\IVirtualFile;
use Pi\VirtualPath\Interfaces\IVirtualDirectory;
use Pi\VirtualPath\Interfaces\IVirtualPathProvider;

abstract class AbstractVirtualFileBase implements IVirtualFile{
	
	public function __construct(
		protected IVirtualPathProvider $provider, 
		IVirtualDirectory $directory
		)
	{
		if(is_null($provider)) {
			throw new \Exception('Provider cant be null');
		} else if(is_null($dire)) {
			throw new \Exception('Directory cant be null');
		}
	}
}