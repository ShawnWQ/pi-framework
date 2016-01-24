<?hh
use Mocks\TestHost;
use Pi\HostConfig;
use Pi\Cache\LocalCacheProvider;

class LocalCacheProviderTest
  extends \PHPUnit_Framework_TestCase {

    protected $host;

  public function setUp()
  {
    $config = new HostConfig();
    $config->setConfigsPath('/tmp/a.json');
    $this->host = new TestHost($config);

    $this->host->init();
  }

  public function testCanRegisterCacheBeforeInit()
  {
    $provider = $this->host->cacheProvider();
    $this->assertFalse(is_null($provider));
    $provider->set('name', 'test');
    $name = $provider->get('name');

    $this->assertTrue($name == 'test');

    $provider->set('other', 'test', true);
    $other = $provider->get('other');
    $this->assertTrue($other == 'test');
  }
}
