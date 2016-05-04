<?hh

use Pi\Queue\PiQueue,
	Pi\Queue\RedisPiQueue,
	Pi\Queue\PiJob,
	Pi\Queue\PiWorker,
	Pi\Queue\JobStatus,
	Pi\Queue\PiQueueMockHost;

class PiQueueHostTest extends \PHPUnit_Framework_TestCase {
	
	protected $host;

	

	public function setUp()
	{
		$this->host = new PiQueueMockHost();

	}

	public function testCanRunHostInCli()
	{
		$this->host->init();
	}
}