<?hh

use Pi\Queue\PiQueue,
	Pi\Queue\RedisPiQueue,
	Pi\Queue\PiJob,
	Pi\Queue\PiWorker,
	Pi\Queue\JobStatus,
	Mocks\BibleHost,
	Mocks\RedisPiQueueServiceTRequest,
	Mocks\RedisPiQueueServiceTResponse;

class RedisPiQueueTest extends \PHPUnit_Framework_TestCase {
	
	protected $host;
	protected $redis;
	
	public function setUp()
	{
		$this->host = new BibleHost();
		$this->host->init();
		$this->redis = $this->host->container()->get('IRedisClientsManager');
	}
	public function testCanExecuteQueue()
	{
		$provider = $this->host->container()->get('Pi\ServiceInterface\AbstractMailProvider');
		$piQueue = $this->getPiQueue();
		$dto = new RedisPiQueueServiceTRequest();
		$piQueue->enqueue('default', 'Mocks\RedisPiQueueServiceT', 'default', $dto);
	}

	public function testCanPushItem()
	{
		$provider = $this->host->container()->get('Pi\ServiceInterface\AbstractMailProvider');
		$piQueue = $this->getPiQueue();
		$dto = new RedisPiQueueServiceTRequest();
		$count = $this->redis->llen('queue::default') ?: 0;

		$piQueue->push('default', array('class' => 'Mocks\RedisPiQueueServiceT', 'request' => 'default', 'dto' => json_encode($dto)));
		$len = $this->redis->llen('queue::default') ?: 0;
		$this->assertEquals($count, ($len - 1));
	}

	public function testCanPopItem()
	{
		$this->redis->lpush('queue::default', json_encode(array('class' => 'none')));
		$count = $this->redis->llen('queue::default');
		$piQueue = $this->getPiQueue();
		$piQueue->pop('default');
		$this->assertTrue($count === ($count = $this->redis->llen('queue::default') - 1));
	}

	private function getPiQueue()
	{
		return $this->host->container()->get('Pi\Queue\PiQueue');
	}
}