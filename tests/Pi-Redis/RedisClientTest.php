<?hh
use Pi\Redis\RedisClient;
use Pi\Common\RandomString;

class RedisClientTest extends \PHPUnit_Framework_TestCase {

	protected $client;

	public function setUp()
	{
		$this->client = new RedisClient();
    $this->client->connect();
	}

  public function testAssertCreatesAnRedisInstance()
  {
  	$this->assertTrue($this->client instanceof \Redis);
  }

  public function testCanSetandGetString()
  {
  	$key = RandomString::generate();
  	$this->client->set($key, 'abc');
  	$this->assertEquals('abc', $this->client->get($key));
  }

  public function testPushListAndGetRange()
  {
    $key = RandomString::generate();
    $this->client->lpush($key, 'new-value');
    $this->assertEquals(count($this->client->lrange($key, 0, -1)), 1);
  }

  public function testDelete()
  {
    $key = RandomString::generate();
    $this->client->set($key, '1');
    $this->assertTrue(is_string($this->client->get($key)));
    $this->client->delete($key);
    $this->assertFalse(is_string($this->client->get($key)));
  }
}
