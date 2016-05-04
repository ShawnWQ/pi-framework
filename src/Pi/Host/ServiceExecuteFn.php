<?hh

namespace Pi\Host;
use Pi\Interfaces\IRequest;

/**
 * 
 */
class ServiceExecuteFn {

	protected $onBeginCallbacks;
	
	protected $onEndCallbacks;

	/**
	 * @param $callback a function receiving the current Request that execute the service method
	 */
	public function __construct(protected (function(IRequest) : void) $callback)
	{
		$this->onBeginCallbacks = Set {};
		$this->onEndCallbacks = Set {};
	}

	public function onBeginInvoke($callback)
	{
		$this->onBeginCallbacks->add($callback);
	}

	public function onEndInvoke($callback)
	{
		$this->onEndCallbacks->add($callback);
	}
	public function beginInvoke()
	{
		foreach($this->onBeginCallbacks as $callback) {
			$callback();
		}
	}

	public function endInvoke()
	{
		foreach($this->onEndCallbacks as $callback) {
			$callback();
		}
	}

	public function invoke($request)
	{
		$this->beginInvoke();
		$f = $this->callback;
		$response = $f($request);
		$this->endInvoke();
		return $response;
	}
}
