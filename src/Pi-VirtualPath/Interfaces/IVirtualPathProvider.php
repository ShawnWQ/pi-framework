<?hh

namespace Pi\VirtualPath\Interfaces;

interface IVirtualPathProvider {
	
	public function getFile() : ?IVirtualFile;

	public function getAllMatchingFiles(string $pattern, int $maxDepth = null);

	public function getAppHost() : IPiHost;
}