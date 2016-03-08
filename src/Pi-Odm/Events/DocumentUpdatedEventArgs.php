<?hh

namespace Pi\Odm\Events;

use Pi\EventArgs;

class DocumentUpdatedEventArgs
  extends EventArgs {

  public function __construct($invoker, $query)
  {
    $this->invoker = $invoker;
    $this->query = $query;
  }

  public function getInvoker()
  {
    return $this->invoker;
  }

  public function getQuery()
  {
    return $this->query;
  }

  protected $invoker;

  protected $query;
}
