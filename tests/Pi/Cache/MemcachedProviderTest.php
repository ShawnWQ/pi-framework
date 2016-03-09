<?hh

use Mocks\BibleHost,
    Pi\Cache\MemcachedProvider,
    Pi\Common\RandomString;




class MemcachedProviderTest extends \PHPUnit_Framework_TestCase {
  

  protected $provider;

  public function setUp()
  {
    $this->provider = new MemcachedProvider(new Map<string,int>(Pair{'localhost', 11211}));
  }

  public function testCanSetAndGetAnStringValue()
  {
    $this->provider->set('a', 'a');
    $r = $this->provider->get('a');
    $this->assertEquals($r, 'a');

    $this->provider->set('a', 'b');
    $r = $this->provider->get('a');
    $this->assertEquals($r, 'b');
  }

}
