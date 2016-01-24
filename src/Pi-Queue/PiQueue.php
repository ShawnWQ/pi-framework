<?hh

namespace Pi\Queue;

use Pi\Common\RandomString,
	Pi\Interfaces\ILog,
	Pi\Interfaces\IContainable,
	Pi\Interfaces\IContainer;



abstract class PiQueue implements IContainable {
	

	public function __construct(
		ILog $logger
	)
	{

	}

	public function ioc(IContainer $ioc) {}

	const DEFAULT_INTERVAL = 5;

	const NAME = 'Pi\Queue\PiQueue';

	//public abstract function add(string $requestId);

	abstract function pop(string $queue);

	abstract function push(string $queue, $item);

	abstract function queues();

	public static function generateJobId()
	{
		return RandomString::generate();
	}

	public function enqueue(string $queue, string $class, string $request, $dto = null, $trackStatus = false)
	{

		if(!is_null($dto) && !is_object($dto)) {
			throw new InvalidArgumentException(
				'Supplied $dto must be an object'
			);
		}

		$id = $this->generateJobId();

		$this->push($queue, array(
			'class' => $class,
			'request' => $request,
			'dto' => array($dto),
			'id' => $id,
			'queue_time' => microtime(true)
		));

		return $id;
	}

	public function reserve(string $queue)
	{
		$payload = $this->pop($queue);
		if(!is_array($payload)) {
			return false;
		}

		return new PibJob($this, $queue, $payload);
	}



	public function schedule((function (IContainer): mixed) $closure, $timeSpan)
	{
		
	}

	public function init()
	{

	}
}