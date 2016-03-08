<?hh

namespace Pi\Odm\Events;

use Pi\EventArgs;
use Pi\Odm\DataDocument;
use Pi\Odm\DataProperty;

class DataPropertyChangeEventArgs
  extends EventArgs {

  public function __construct($invoker, $query,
    protected DataDocument $document, protected DataProperty $property, protected $value)
  {
    $this->invoker = $invoker;
    $this->query = $query;
  }

  public function getValue()
  {
    return $this->value;
  }

  public function getProperty()
  {
    return $this->property;
  }

  public function getDocument()
  {
    return $this->document;
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
