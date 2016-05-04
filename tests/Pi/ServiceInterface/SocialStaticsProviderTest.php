<?hh
use Pi\ServiceInterface\SocialStaticsProvider,
	Mocks\BibleHost;

class SocialStaticsProviderTest
  extends \PHPUnit_Framework_TestCase  {

  protected $host;

  public function setUp()
  {
    $this->host = new BibleHost();
    $this->host->init();
  }

  public function testCanGetSharesFromAllProviders()
  {
    
    $uri = 'https://codigo.ovh';
    $svc = $this->host->container()->get('Pi\ServiceInterface\SocialStaticsService');
    $shares = $svc->getShares($uri);

    $this->assertTrue(count($shares['facebook']) > 0);

  }
}
