<?hh

use Mocks\BibleHost,
    Pi\Host\RoutesManager,
    Pi\NotImplementedException,
    Mocks\HttpRequestMock;




class RoutesManagerTest extends \PHPUnit_Framework_TestCase {

  private $host;

  private $routes;

  public function setUp()
  {
      $this->host = new BibleHost();
      $this->host->init();
      $this->routes = new RoutesManager($this->host);
  }

  public function testRegisterRoutesUsingAddAndGet()
  {
    $this->routes->add('/test', 'TestService', get_class(new HttpRequestMock($this->host)), array('GET'));
    $route = $this->routes->get('/test');
    $this->assertNotNull($route);
    $route = $this->routes->get('/teste');
    $this->assertNull($route);
    $route = $this->routes->get('/test', 'GET');
    $this->assertNotNull($route);
    $route = $this->routes->get('/test', 'POST');
    $this->assertNull($route);
    $route = $this->routes->get('/test', 'DELETE');
    $this->assertNull($route);
  }
}
