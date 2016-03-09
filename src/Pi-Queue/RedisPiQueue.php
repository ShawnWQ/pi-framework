<?hh

namespace Pi\Queue;

use Pi\Interfaces\ILog,
	Pi\Redis\Interfaces\IRedisClient;



class RedisPiQueue extends PiQueue {

	public function __construct(
		ILog $logger,
		protected IRedisClient $redis)
	{
		parent::__construct($logger);
	}

	/**
	 * Get an array of all know queues
	 * @return array Array of queues
	 */
	public function queues() : array
	{
		$queues = $this->redis->smembers('queues');
		if(!is_array($queues))
		{
			$queues = array();
		}
		return $queues;
	}

	public function pop(string $queue) : ?array
	{
		$item = $this->redis->lpop('queue::' . $queue);
		if(!$item) {
			return;
		}

		return json_decode($item, true);
	}

	public function push(string $queue, $item)
	{
		$this->redis->sadd('queues', $queue);
		$length = $this->redis->rpush('queue::' . $queue, json_encode($item));
		if($length < 1) {
			return false;
		}
		return true;
	}

	public function size(string $queue)
	{
		return $this->redis->llen('queue::' . $queue);
	}

	public function removeQueue(string $queue)
	{
		$counter = $this->removeList($queue);
		$this->redis->srem('queues', $queue);
		return $counter;
	}

	public function removeList(string $queue)
	{
		$counter = $this->size($queue);
		$result = $this->redis->del('queue::' . $queue);
		return $result == 1 ? $counter : 0;
	}

	public function removeItems(string $queue, array $items)
	{
		$counter = 0;
		$originalQueue = 'queue::' . $queue;
		$tempQueue = $originalQueue  . '::temp::' . time();
		$requeueQueue = $tempQueue . '::requeue';

		$finished = false;
		// move items from original queue to temporary queue
		while(!$finished) {
			$string = $this->redis->rpoplpush($originalQueue, $tempQueue);
			if(!empty($string)) {
				if($this->matchItem($string, $items)) {
					$this->redis->rpop($tempQueue);
					$counter++;
				} else {
					$this->redis->rpoplpush($tempQueue, $requeueQueue);
				}
			} else {
				$finished = true;
			}
		}

		$finished = false;
		// move back from tempQueue t original
		while(!$finished) {
			$string = $this->redis->rpoplpush($requeueQueue, $originalQueue);
			if(empty($string)) {
				$finished = true;
			}
		}

		$this->redis->del($requeueQueue);
		$this->redis->del($tempQueue);

		return $counter;
	}
}