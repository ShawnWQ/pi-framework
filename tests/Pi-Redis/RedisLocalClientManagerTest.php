<?hh

use Pi\Redis\RedisLocalClientManager;
use Pi\Redis\RedisFactory;
use Pi\Redis\RedisCLient;

class RedisLocalClientManagerTest extends \PHPUnit_Framework_TestCase{


  public function testGetClient()
  {
	$factory = new RedisFactory();
	$manager = new RedisLocalClientManager($factory);
	$client = $manager->getClient();

	$this->assertTrue($client instanceof RedisCLient);
  }
}
