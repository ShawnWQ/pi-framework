<?hh

namespace Pi\Queue;

use Pi\PiHost,
	Pi\Interfaces\IContainer,
	Pi\Redis\Interfaces\IRedisClient;

abstract class PiQueueHost extends PiHost {

	public function afterInit()
	{
		/*
		 * TO AFTER INIT, WHEN DEPENDENCIES ARE RESOLVED
		 */
 		$interval = 0;
		$blocking = false;
		$queues = array('default');
		$factory = $this->container->get('Pi\Interfaces\ILogFactory');
    $logger = $factory->getLogger(PiWorker::NAME);
		$redis = $this->container->get('IRedisClientsManager');
		if(is_null($redis) || !$redis instanceof IRedisClient) {
			throw new \Exception('Redis is not registered');
		}
		$worker = new PiWorker($this->container->get('Pi\Queue\PiQueue'), $queues, $logger, $redis);

		$worker->work($interval, $blocking);
	}
}
