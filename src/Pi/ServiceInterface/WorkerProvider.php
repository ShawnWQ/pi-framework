<?hh

namespace Pi\ServiceInterface;

use Pi\Interfaces\ICacheProvider;

enum WorkerType {
	Normal = 1,
	Urgent = 2,
	LongRunning = 3
}

/**
 * @name Worker Provider
 * @description
 * The worker provider is responsible for handling background requests with a WorkerHost
 * The provider register two types of requests:
 * - instant: executed as soon as posible
 * - schedule: tasks schedules with 20 minutos space between then
 *
 */
class WorkerProvider {
	
	const CACHE_KEY_WORKER_QUEUE = 'workers';
	const CACHE_KEY_WORKER_SCHEDULE = 'scheduled';
	
	public function __construct
	(
		protected IRedisClient $redis
	)
	{

	}

	public function schedule(\DateTime $when, string $requestId, \JsonSerializable $request)
	{

	}

	protected function getBucketKey(\DateTime $when)
	{
		return $when->toString();
	}

	public function register(WorkerType $type, string $requestId, \JsonSerializable $request)
	{
		$minutes = array(5, 10, 15, 20, 25, 30, 35, 40, 45, 50);

	}
}