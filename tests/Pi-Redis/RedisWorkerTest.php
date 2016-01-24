<?hh
use Pi\Redis\RedisWorker;
use Pi\Common\RandomString;

class RedisWorkerTest extends \PHPUnit_Framework_TestCase {

	protected $client;

	public function setUp()
	{
		$this->worker = new RedisWorker();
    
	}
  public function testCanStart()
  {
    	
  }
}
