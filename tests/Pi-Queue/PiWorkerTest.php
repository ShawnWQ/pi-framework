<?hh

use Pi\Queue\PiQueue,
	Pi\Queue\RedisPiQueue,
	Pi\Queue\PiJob,
	Pi\Cache\InMemoryCacheProvider,
	Pi\EventManager,
	Pi\Queue\PiWorker,
	Pi\Queue\JobStatus,
	Pi\Redis\RedisWorker,
	Pi\Redis\RedisClient,
	Pi\Common\RandomString,
	Pi\Logging\DebugLogger,
	Mocks\RedisPiQueueServiceTRequest,
	Mocks\MockHydratorFactory,
	Mocks\MockMappingDriver,
	Mocks\MockMetadataFactory;


class RedisWorkerTest extends \PHPUnit_Framework_TestCase {

	const QUEUE_CLASS = 'Mocks\RedisPiQueueServiceT';

	protected $em;

	protected $cache;

	protected $metadataFactory;

	protected $hydratorFactory;

	protected $redis;

	protected $logger;

	protected $client;

	protected $worker;

	protected array $queues;

	protected int $interval;

	protected bool $blocking;

	public function setUp()
	{
		$this->init();
		$key = RandomString::generate();
		$queue = new RedisPiQueue($this->logger,$this->redis);
		$dto = new RedisPiQueueServiceTRequest();
		$queue->enqueue($key, self::QUEUE_CLASS, $dto);
		$this->worker = new PiWorker($queue, $this->queues, $this->logger, $this->redis);
		
   		
	}

	public function init()
	{
		$this->interval = 0;
		$this->blocking = false;
		$this->logger = new DebugLogger();
		$this->em = new EventManager();
		$this->queues = array('default');
		$this->cache = new InMemoryCacheProvider();
		$this->metadataFactory = new MockMetadataFactory($this->em, new MockMappingDriver(array(), $this->em, $this->cache));
		$this->hydratorFactory = new MockHydratorFactory(
			$this->metadataFactory,
		  	'Mocks\\Hydrators',
		   	sys_get_temp_dir()
		);
		$this->redis = new RedisClient($this->hydratorFactory);
		$this->redis->connect();
	}

	

	public function testCanStart()
	{
		$this->worker->work($this->interval, $this->blocking);

	}
}
