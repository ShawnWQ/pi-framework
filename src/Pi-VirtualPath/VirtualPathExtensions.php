<?hh

namespace Pi\VirtualPath;


use Pi\VirtualPath\Interfaces\IVirtualFile;
use Pi\VirtualPath\Interfaces\IVirtualDirectory;
use Pi\VirtualPath\Interfaces\IVirtualPathProvider;


class VirtualPathExtensions {
	
	public static function TokenizeVirtualPath(string $path, IVirtualPathProvider $provider) : Set<string>
	{
		if(empty($path)) 
			return new Set<string>{};

		$tokens = explode('/', $path);
		return array_reverse($tokens);
	}
}