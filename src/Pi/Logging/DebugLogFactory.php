<?hh

namespace Pi\Logging;

use Pi\Interfaces\ILogFactory,
    Pi\Interfaces\ILogFactor,
    Pi\Interfaces\IContainable,
    Pi\Interfaces\IContainer,
    Pi\Logging\DebugLogger;




class DebugLogFactory implements ILogFactory, IContainable{

  private $debugEnabled;

  public function __construct($debugEnabled = true) {
    $this->debugEnabled = $debugEnabled;
  }

  public function getLogger($type)
  {
    $debugger= new DebugLogger($type);
    //$debugger->isDebugEnabled = $this->debugEnabled;
    return $debugger;
  }

  public function ioc(IContainer $ioc)
  {

  }
}
