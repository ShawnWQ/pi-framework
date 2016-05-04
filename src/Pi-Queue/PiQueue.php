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

	const DEFAULT_INTERVAL = 5;

	const JOB_ID = 'job::';

	const NAME = 'Pi\Queue\PiQueue';

	//public abstract function add(string $requestId);

	abstract function pop(string $queue);

	abstract function size(string $queue);

	abstract function push(string $queue, $item);

	abstract function queues();

	public function ioc(IContainer $ioc) {}

	/**
	 * Generate a new Job Id
	 * @return [type] [description]
	 */
	public static function generateJobId()
	{
		return self::JOB_ID . RandomString::generate();
	}

	/**
	 * Create a Queue item
	 * @param  string $class the name of the DTO class
	 * @param  [type] $dto   the DTO class
	 * @param  [type] $id    the Id of the job
	 * @param  [type] $time  creation time of the Job
	 * @return [type]        [description]
	 */
	public static function createItemArray(string $class, $dto, $id, $time = null)
	{
		return array(
			'class' => $class,
			//'request' => $request,
			'dto' => array($dto),
			'id' => $id,
			'queue_time' => $time ?: microtime(true)
		);
	}

	/**
	 *  item can be ['class'] or ['class' => 'id'] or ['class' => {:foo => 1, :bar => 2}]
	 * @param  string $value [description]
	 * @param  array  $items [description]
	 * @return boolean true if match
	 */
	public function matchItem(string $val, array $items) 
	{
		$decoded = json_decode($val, true);

		foreach ($items as $key => $value) {

			if(is_array($value)) {
				if($value['id'] == $decoded['id']) {
					return true;
				}
				$decodedArgs = (array)$decoded['dto'][0];
				if($decoded['class'] == $key && 
					count($decodedArgs) > 0 && count(array_diff($decodedArgs, $value)) == 0) {
					return true;
				}

			} else if(is_numeric($key)) {
				
				if($decoded['class'] == $value) {
					return true;
				}
				
			} else {
				if($decoded['class'] == $key && $decoded['id'] == $value) {
					return true;
				}
			}
		}
		return false;
	}



	public function dequeue(string $queue, array $items)
	{
		if(count($items) > 0) {
			return $this->removeItems($queue, $items);
		} else {
			return $this->removeList($queue);
		}
	}


	/**
	 * Add a DTO to a specific queue
	 * Uses the abstract implementation of push
	 * @param  string  $queue       the name of the queue to place the job in
	 * @param  string  $class       the name of the class
	 * @param  object  $dto         the DTO class
	 * @param  boolean $trackStatus if true, track the status of the queue
	 * @return string               the job id
	 */
	public function enqueue(string $queue, string $class, $dto = null, $trackStatus = false)
	{
		if(!is_null($dto) && !is_object($dto)) {
			throw new InvalidArgumentException(
				'Supplied $dto must be an object'
			);
		}
		$id = $this->generateJobId();
		$this->push($queue, self::createItemArray($class, $dto, $id));

		return $id;
	}

	public function reserve(string $queue)
	{
		$payload = $this->pop($queue);
		if(!is_array($payload)) {
			return false;
		}
		return new PiJob($this, $queue, $payload);
	}



	public function schedule((function (IContainer): mixed) $closure, $timeSpan)
	{
		
	}

	public function init()
	{

	}
}