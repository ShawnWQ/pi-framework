<?hh

namespace Pi\VirtualPath\Interfaces;

interface IVirtualDirectory {
	
	/**
	 * Get the last time this object was modified
	 */
	public function getLastModified() : ?DateTime;

	/**
	 * Get the parent directory or null if is root
	 */
	public function gerParentDirectory() : ?IVirtualDirectory;

	/**
	 * Get a file inside the directory
	 */
	public function getFile(string $path) : ?IVirtualFile;

	/**
	 * Get a directory inside this directory
	 */
	public function getDirectory(string $path) : ?IVirtualDirectory;

	/**
	 * Get the virtual path of this directory
	 */
	public function getVirtualPath() : string;

	/**
	 * Return true wether this is a directory
	 */
	public function getIsDirectory() : bool;

	/**
	 * Indicates if the directory is root
	 */
	public function getIsRoot() : bool;

	/**
	 * Return the files from this directory
	 */
	public function getFiles(bool $recursive = false, int $skip = 0, int $take = null) : array;

	public function getDirectories(bool $recursive = true, int $skip = 0, int $take = null) : array;

	public function getName() : string;
/*
	abstract function getFileFromDirectory(string $fileName) : ?IVirtualFile;

	abstract function getMatchinFilesFromDirectory(string $pattern) : ?array;

	abstract function getDirectoryFromPhpDirectoryOrDefault() : ?IVirtualDirectory;

	abstract function getMatchingFilesInDir(string $pattern) : ?array;
	*/
}