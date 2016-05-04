<?hh

namespace Pi\Host;
use Pi\EventArgs;

class PostPostGenerateHydratorArgs extends EventArgs {

  public function __construct(
    protected string $className, 
    protected Operation $operation)
  {
    
  }

  public function getClassName()
  {
    return $this->className;
  }

  public function getOperation()
  {
    return $this->operation;
  }
}
