<?hh

use Pi\Queue\PiQueue,
	Pi\Queue\RedisPiQueue,
	Pi\Queue\PiJob,
	Pi\Queue\PiWorker,
	Pi\Queue\JobStatus,
	Pi\Common\RandomString,
	Mocks\BibleHost,
	Mocks\RedisPiQueueServiceTRequest,
	Mocks\RedisPiQueueServiceTResponse;

class RedisPiQueueTest extends \PHPUnit_Framework_TestCase {
	
	protected $host;
	protected $redis;

	const QUEUE_CLASS = 'Mocks\RedisPiQueueServiceT';
	
	public function setUp()
	{
		$this->host = new BibleHost();
		$this->host->init();
		$this->redis = $this->host->container()->get('IRedisClientsManager');
	}

	public function testCanReserve()
	{
		$key = RandomString::generate();
		$piQueue = $this->getPiQueue();
		$dto = new RedisPiQueueServiceTRequest();

		$piQueue->enqueue($key, self::QUEUE_CLASS, $dto);
		$this->assertTrue($piQueue->size($key) == 1);
		
		$job = $piQueue->reserve($key);
		$this->assertTrue($job instanceof PiJob);
		$this->assertTrue($piQueue->size($key) == 0);
	}
	
	public function testCanEnqueueDtoAndThenDequeue()
	{
		$key = RandomString::generate();
		$provider = $this->host->container()->get('Pi\ServiceInterface\AbstractMailProvider');
		$piQueue = $this->getPiQueue();
		$dto = new RedisPiQueueServiceTRequest();
		$id = $piQueue->enqueue($key, self::QUEUE_CLASS, $dto);
		$count = $piQueue->size($key);
		$this->assertTrue($count == 1);

		$item = $piQueue::createItemArray(self::QUEUE_CLASS, $dto, $id);
		$counter = $piQueue->removeItems($key, array($item));
		$this->assertEquals($counter, 1, $id); // removed 1
		$count = $piQueue->size($key);
		$this->assertTrue($count == 0);
	}

	public function testCanPushItem()
	{
		$provider = $this->host->container()->get('Pi\ServiceInterface\AbstractMailProvider');
		$piQueue = $this->getPiQueue();
		$dto = new RedisPiQueueServiceTRequest();
		$count = $piQueue->size('default');

		$piQueue->push('default', array('class' => 'Mocks\RedisPiQueueServiceT', 'request' => 'default', 'dto' => json_encode($dto)));
		$len = $piQueue->size('default');
		$this->assertEquals($count, ($len - 1));
	}

	public function testCanPopItem()
	{
		$key = RandomString::generate();
		$piQueue = $this->getPiQueue();
		$this->redis->lpush('queue::' . $key, json_encode(array('class' => 'none', 'id' => PiQueue::generateJobId())));
		$count = $piQueue->size($key);
		$piQueue->pop($key);
		$this->assertTrue($count === $piQueue->size($key));
	}

	private function getPiQueue()
	{
		return $this->host->container()->get('Pi\Queue\PiQueue');
	}
}