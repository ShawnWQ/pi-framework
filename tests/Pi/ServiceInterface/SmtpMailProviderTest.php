<?hh

use Pi\AppHost;
use Pi\PiHost;
use Pi\Host\ServiceController;
use Pi\Interfaces\IPiHost;
use Pi\Interfaces\IHasAppHost;
use Mocks\BibleHost;
use Mocks\VerseGetResponse;
use Mocks\MockEnvironment;
use Mocks\TestAppHost;
use Pi\PhpUnitUtils;
use Pi\ServiceInterface\SmtpMailProvider;

class SmtpMailProviderTest
  extends \PHPUnit_Framework_TestCase {

    public function setUp()
    {
      MockEnvironment::mock();
    }

    public function testProviderIsRegistered()
    {
      $host = $this->createAppHost();
      $provider = $host->container->get('Pi\ServiceInterface\AbstractMailProvider');
      $this->assertTrue($provider instanceof SmtpMailProvider);
    }

    public function testCanSendMessage()
    {
      $host = $this->createAppHost();
      $provider = $host->container->get('Pi\ServiceInterface\AbstractMailProvider');
      $provider->send('Test Name', 'test@guilhermecardoso.pt', 'test subject', '<p>the html <b>body</b></p>');
    }
    private function createAppHost()
    {
      return new TestAppHost(new \Pi\HostConfig());
    }

    
  }
