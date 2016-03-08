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

	public function pop(string $queue)
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
}