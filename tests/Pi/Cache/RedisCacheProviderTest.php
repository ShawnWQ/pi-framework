<?hh
use Mocks\BibleHost,
  Pi\Cache\RedisCacheProvider,
  Pi\Common\RandomString;

class RedisCacheProviderTest
  extends \PHPUnit_Framework_TestCase {
    protected $host;

  public function setUp()
  {
    $this->host = new BibleHost(new \Pi\HostConfig());
    $redis = $this->host->container()->get('IRedisClientsManager');
    $this->host->registerCacheProvider(new RedisCacheProvider($redis));
    $this->host->init();
  }

  public function testRegisterRedisCacheProvider()
  {


    $provider = $this->host->cacheProvider();
    $this->assertFalse(is_null($provider));
  }

  public function testCanSetAndGetAnStringValue()
  {
    $provider = $this->host->cacheProvider();
    $provider->set('a', 'a');
    $r = $provider->get('a');
    $this->assertEquals($provider, 'b');
  }

  public function testAddCheckIfContains()
  {
    $provider = $this->host->cacheProvider();
    $list = RandomString::generate();
    $key = RandomString::generate();
    $this->assertFalse($provider->contains($list, $key));
    
    $provider->add($list, $key, $key);
    $this->assertTrue($provider->contains($list, $key));
  }
}
