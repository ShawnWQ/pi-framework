<?hh

use Pi\Redis\RedisClient;
use Pi\Redis\RedisFactory;

class RedisFactoryTest extends \PHPUnit_Framework_TestCase{

  public function testRedisFactoryCanCreateClient()
  {

    $factory = new RedisFactory();
    $client = $factory->createClient(null);

    $this->assertTrue($client instanceof RedisClient);
   
  }
}
