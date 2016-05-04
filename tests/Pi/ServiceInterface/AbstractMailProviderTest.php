<?hh

use Pi\AppHost;
use Pi\PiHost;
use Pi\Host\ServiceController;
use Pi\Interfaces\IPiHost;
use Pi\Interfaces\IHasAppHost;
use Pi\Common\RandomString;
use Mocks\BibleHost;
use Mocks\VerseGetResponse;
use Mocks\MockEnvironment;
use Mocks\TestAppHost;
use Pi\PhpUnitUtils;
use Pi\ServiceInterface\SmtpMailProvider;

class AbstractMailProviderTest
  extends \PHPUnit_Framework_TestCase {

    public function setUp()
    {
      MockEnvironment::mock();
    }

    public function testCanCacheDefaultConfig()
    {
      $host = $this->createAppHost();
      $host->init();
      $provider = $this->getProvider($host);
      $provider->configure($host->container());
      $this->assertTrue($provider->isCached());
      
    }

    public function testCanUpdateConfig()
    {
      $host = $this->createAppHost();
      $host->init();
      $provider = $this->getProvider($host);
      $header = RandomString::generate();
      $footer = RandomString::generate();
      $provider->update($footer, $header);
      $this->assertEquals($footer, $provider->getBodyFooter());
      $this->assertEquals($header, $provider->getBodyHeader());
    }

    private function getProvider($host)
    {      
      return $host->container->get('Pi\ServiceInterface\AbstractMailProvider');
    }

    private function createAppHost()
    {
      return new TestAppHost(new \Pi\HostConfig());
    }

    
  }
