<?hh

namespace Pi\Logging;
use Pi\Interfaces\ILog;

class DebugLogger extends AbstractLogger implements ILog{

  const DEBUG = "DEBUG";
  const ERROR = "ERROR";
  const FATAL = "FATAL";
  const INFO = "INFO";
  const WARN = "WARN";

  protected $logFile;

  protected $separator = '.';

  protected $logIndividualRequests = false;

  public function __construct(protected $type = null)
  {
    if(is_null($type)){
      $type='';
    }

    $this->separator = '.';
    $this->logFile = '/tmp/pi.log';
  }

  private static function log($message, $exception = null)
  {
    $path = '/tmp/pi.log';

    if(!is_writable($path)){
    //  throw new \Exception(sprintf('The DebugLogger save directory isnt writable. Fix permissions for: %s', $path));
    }

    $fd = fopen($path, "a");
    fwrite($fd, $message);
    fclose($fd);

    //if($this->logIndividualRequests) {
      //$temp_file = tempnam(sys_get_temp_dir(), 'Tux');
      //fopen($temp_file, "a");

    //}

  }

  private function formatMessage($level, string $message, ?\Exception $exception = null)
  {
    $debugBacktrace = debug_backtrace();
		$line = $debugBacktrace[1]['line'];
		$file = $debugBacktrace[1]['file'];
    $datetime = @date("Y-m-d H:i:s");
    if(is_null($exception)) {
      return sprintf("[%s] %s %s %s at line %s of %s\n\r", $this->type, $datetime, $level, $message, $line, $file);
    }
    else {
      $trace = $exception->getTrace();

    $ex = 'Exception: "';
    $ex .= $exception->getMessage();
    $ex .= '" @ ';
    if($trace[0]['class'] != '') {
      $ex .= $trace[0]['class'];
      $ex .= '->';
    }
    $ex .= $trace[0]['function'];
    $ex .= '();\n\r';

      return sprintf("[%s] %s %s %s at line %s of %s\n\rException trace: %s\n\r", $this->type, $datetime, $level, $message, $line, $file, $ex);
    }
  }

  public function debug(string $message)
  {

    self::log($this->formatMessage(self::DEBUG, $message));
  }

  public function error($message)
  {
    self::log(self::ERROR . $message);
  }

  public function fatal($message, ?\Exception $exception = null)
  {
    self::log(self::FATAL . $message, $exception);
  }

  public function info($message)
  {
    self::log(self::INFO . $message);
  }

  public function warn($message)
  {
    self::log(self::WARN . $message);
  }
}
