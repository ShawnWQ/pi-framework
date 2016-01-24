<?hh

namespace Mocks;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;

class DumbDependency
  implements IContainable {
  protected $dumb;
    public function ioc(IContainer $container)
    {

    }
  public function getDumb()
  {
    return $this->dumb;
  }
  public function setDumb($d)
  {
    $this->dumb = $d;
  }
  public function __construct(){
    $this->msg = 'constructed';
  }

  public function msg()
  {
    return $this->msg;
  }

  public static function iocType() : string
  {
    return get_called_class();
  }

  protected $msg;
}
