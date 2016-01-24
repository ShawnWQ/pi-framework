<?hh

namespace Pi\FileSystem;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;

class FileSystemConfiguration implements IContainable {

  public function ioc(IContainer $ioc)
  {

  }

  public function storeDir($value = null)
  {
    if($value === null) return $this->storeDir;
    $this->storeDir = $value;
  }

  protected $storeDir;
}
