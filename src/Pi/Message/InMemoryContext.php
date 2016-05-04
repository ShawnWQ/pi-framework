<?hh
namespace Pi\Message;

/**
 * InMemoryContext keeps the subscriptions in memory
 */
class InMemoryContext {
  /**
   * Subscriptions handlers
   * @var array
   */
  protected $handlers = array();

  public function add($request, $listener)
  {
    $id = $id = spl_object_hash($listener);
    $this->handlers[$request][$id] = $listener;
  }

  public function get($key = null)
  {
    return is_null($key) ? $this->handlers : $this->handlers[$key];
  }
}
