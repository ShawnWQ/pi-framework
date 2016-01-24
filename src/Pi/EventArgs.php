<?hh

namespace Pi;
use Pi\Interfaces\IEventArgs;

/**
 * Default event arguments
 */
class EventArgs
  implements IEventArgs {

  protected static $instance;

  public static function getInstance()
  {
    if(!self::$instance) {
      self::$instance = new EventArgs();
    }

    return self::$instance;
  }
}
