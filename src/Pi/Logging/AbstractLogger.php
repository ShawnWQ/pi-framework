<?hh

namespace Pi\Logging;
use Pi\Interfaces\IContainable,
	Pi\Interfaces\IContainer;




abstract class AbstractLogger implements IContainable {
	
	public abstract function debug(string $message);

	public abstract function error($message);

	public function errorEx(\Exception $ex)
	{
		return $this->error($ex->getMessage());
	}

	public abstract function fatal($message, ?\Exception $exception = null);

	public abstract function info($message);

	public abstract function warn($message);

	public function ioc(IContainer $ioc)
	{

	}
}