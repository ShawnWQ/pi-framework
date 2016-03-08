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

class AppHostTest
  extends \PHPUnit_Framework_TestCase {

    public function setUp()
    {
      MockEnvironment::mock();
    }

    public function testResponseIsWrittenWithDefaults()
    {
      $response = $host = $this->createAppHost();
      // stop the output, after init check if any
      //$output = $host->init();
    }

    private function createAppHost()
    {
      return new TestAppHost(new \Pi\HostConfig());
    }

    public function testRemoveQueryParameters()
    {
      $host = $this->createAppHost();
      $method = PhpUnitUtils::getMethod($host, 'removeQueryParameters');
      $uri = $method->invoke($host, '/api/init?query=sim');
      $this->assertEquals($uri, '/api/init');
    }

    public function testRemoveTrailSlash()
    {
      $host = $this->createAppHost();
      $method = PhpUnitUtils::getMethod($host, 'removeTrailSlash');
      $uri = $method->invoke($host, '/api/init/');
      $this->assertEquals($uri, '/api/init');
    }

    public function testGetHttpMethod()
    {
      $host = $this->createAppHost();
      $_SERVER['REQUEST_METHOD'] = 'POST';
      $method = PhpUnitUtils::getMethod($host, 'getHttpMethod');
      $method = $method->invoke($host);
      $this->assertEquals($method, 'POST');

      $_SERVER['REQUEST_METHOD'] = 'PUTT';
      $method = PhpUnitUtils::getMethod($host, 'getHttpMethod');
      $method = $method->invoke($host);
      $this->assertEquals($method, 'GET');
    }
    
  }
