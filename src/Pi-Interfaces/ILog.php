<?hh
namespace Pi\Interfaces;

/**
 * ILog interface
 */
interface ILog {
/**
 * Log a Debug message
 * @param type $message
 * @return type
 */
  public function debug(string $message);
  /**
 * Log a Error message
 * @param type $message
 * @return type
 */
  public function error($message);
  /**
 * Log a Fatal message
 * @param type $message
 * @return type
 */
  public function fatal($message);
  /**
 * Log a Info message
 * @param type $message
 * @return type
 */
  public function info($message);
  /**
 * Log a Warn message
 * @param type $message
 * @return type
 */
  public function warn($message);
}
