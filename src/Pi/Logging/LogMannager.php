<?hh

namespace Pi\Logging;

/**
 * @name Log Mannager
 *
 * @description
 * Use Log Manager to log specific types
 *
 */
class LogMannager {

  private static $logFactory;

  public static function getLogFactory()
  {
    if(is_null(self::$logFactory)){
      self::$logFactory = new DebugLogFactory();
    }
    return self::$logFactory;
  }

  /**
   * @param $type the Type the logger must get
   */
  public static function getLogger($type)
  {
    return self::getLogFactory()->getLogger($type);
  }
}
