<?hh

namespace Pi\Common\Mapping\Driver;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;

abstract class AbstractMappingDriver
  implements IContainable {

    
  public function __construct(protected array $paths = array())
  {

  }

  public function ioc(IContainer $container){
    $this->paths = array();
  }
}
