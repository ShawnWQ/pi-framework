<?hh

namespace Pi;
use SplFileObject;

/**
 * A File Mapper heavly inspired (at begining was just copied) from HackPack: https://github.com/HackPack/HackClassScanner
 * Find files and folders recursivly
 */
final class PiFileMapper {

	private bool $findClasses = false;
    private bool $findInterfaces = false;
    private Vector<(function (string) : bool)> $fileFilters = Vector{};
    private Vector<(function (string) : bool)> $classFilters = Vector{};

    public function __construct(public Set<string> $paths, public Set<string> $excludes = Set{})
    {
        $this->paths = $paths
            ->filter($p ==> $p !== '' && (is_dir($p) || is_file($p)))
            ->map($p ==> realpath($p));

        $this->excludes = $excludes
            ->filter($p ==> $p !== '' && (is_dir($p) || is_file($p)))
            ->map($p ==> realpath($p));
    }

    public function cache()
    {
    	$map = $this->getMap();
    	// get $cacheProvider;
    }

    public function getMap(): Map<string,string>
    {
    	$this->findClasses = true;
    	$this->findInterfaces = true;
    	return $this->mapAll();
    }

    public function getMapByExtension(string $extension) : Map<string,tring>
    {
        $this->findClasses = false;
        $this->findInterfaces = false;
        return $this->mapAll($extension);
    }

    private function mapAll($extension = null) : Map<string,string>
    {
    	$classMap = Map{};
    	foreach($this->paths as $path) {
                $this->findRecursive($path, $classMap, $extension);
    	}
    	return $classMap;
    }


    private function findRecursive(string $path, Map<string,string> $classMap, $extension = null): void
    {
    	// First see if the class was registered
    	foreach(new \FileSystemIterator($path) as $finfo) {
            if($finfo->isDir() && ! $this->excludes->contains($finfo->getRealPath())) {
                $this->findRecursive($finfo->getRealPath(), $classMap, $extension);
            } elseif(
                $finfo->isFile() &&
                ! $this->excludes->contains($finfo->getRealPath()) &&
                $this->alreadyAdded($finfo->getRealPath())
            ) {

                if(!is_null($extension) && $finfo->getExtension() !== $extension) {
                    continue;
                }
                $className = $this->parseFile($finfo->openFile());
                if($className !== '') {
                    $classMap[$className] = $finfo->getRealPath();
                } else {
                    $classMap[$finfo->getFilename()] = $finfo->getRealPath();
                }
            }
        }
    }

    private function parseFile(SplFileObject $file) : string
    {
    	 // incrementally break contents into tokens
        $namespace = $class = $buffer = '';
        $i = 0;
        while ($class === '') {
            if ($file->eof()) {
                // No class to find
                return '';
            }
            // Load 30 lines at a time
            for($newline = 0; $newline < 30; $newline++) {
                $buffer .= $file->fgets();
            }
            if (strpos($buffer, '{') === false) {
                // Class definition requires braces
                continue;
            }
            $tokens = token_get_all($buffer);
            for (; $i < count($tokens); $i++) {
                //search for a namespace
                if ($tokens[$i][0] === \T_NAMESPACE) {
                    for ($j = $i + 1; $j < count($tokens); $j++) {
                        // Namespace ends on { or ;
                        if (is_array($tokens[$j])) {
                            $namespace .= trim((string) $tokens[$j][1]);
                        } elseif ($tokens[$j] === '{' || $tokens[$j] === ';') {
                            break;
                        } else {
                            $namespace .= (string) $tokens[$j];
                        }
                    }
                }
                //search for the class name
                if (
                    ($this->findClasses && $tokens[$i][0] === \T_CLASS) ||
                    ($this->findInterfaces && $tokens[$i][0] === \T_INTERFACE)
                ) {
                    for ($j = $i + 1; $j < count($tokens); $j++) {
                        if($tokens[$j] === '{') {
                            // We found one!
                            $class = $tokens[$i + 2][1];
                            break;
                        } elseif($tokens[$j] === '}' || $tokens[$j] === ';') {
                            // We found ::class inside of a context
                            $class = '';
                            break;
                        }
                    }
                }
                if($class !== '') {
                    // Stop looking for the class name after it is found
                    break;
                }
            }
        }
        if($namespace !== '' && substr($namespace, 0, 1) !== '\\') {
            $namespace = '\\' . $namespace;
        }
        $fullname = implode('\\', [$namespace, $class]);
        if($this->alreadyAdded($fullname)){
            return $fullname;
        }
        return '';
    }

    private function alreadyAdded(string $fileName) : bool
    {
    	foreach($this->fileFilters as $filter) {
            if( ! $filter($fileName)){
                return false;
            }
        }
        return true;
    }

}
