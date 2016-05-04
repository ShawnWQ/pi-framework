<?hh
use Mocks\BibleHost;
use Pi\HostConfig;
use Pi\Cache\InMemoryCacheProvider;

class InMemoryCacheProviderTest
  extends \PHPUnit_Framework_TestCase {

    protected $host;

  public function setUp()
  {
    $this->host = new BibleHost();
    $this->host->container->register('ICacheProvider', function(){
      return new InMemoryCacheProvider();
    });
    $this->host->init();
  }

  public function testCanRegisterCacheBeforeInit()
  {
    $provider = $this->host->cacheProvider();
    $this->assertTrue($provider instanceof InMemoryCacheProvider);
    $provider->set('name', 'test');
    $name = $provider->get('name');

    $this->assertTrue($name == 'test');

    $provider->set('other', 'test', true);
    $other = $provider->get('other');
    $this->assertTrue($other == 'test');
  }
}
