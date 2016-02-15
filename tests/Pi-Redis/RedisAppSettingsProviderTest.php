<?hh

namespace Test\Redis;

use Pi\Redis\RedisClient,
	Pi\Redis\RedisAppSettingsProvider,
	Pi\Common\RandomString;




class RedisAppSettingsProviderTest extends \PHPUnit_Framework_TestCase {
	
	protected $provider;

	protected $redis;

	public function setUp()
	{
		$this->redis = new RedisClient();
		$this->provider = new RedisAppSettingsProvider($this->redis);
	}

	public function testCanSetAndGetString()
	{
		$val = RandomString::generate();
		$key = RandomString::generate();
		$this->provider->setString($key, $val);

		$current = $this->provider->getString($key);
		$this->assertEquals($current, $val);
	}
}