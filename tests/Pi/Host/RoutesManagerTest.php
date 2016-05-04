<?hh

use Pi\NotImplementedException,
    Pi\Route,
    Pi\Host\RoutesManager,
    Pi\Common\RandomString,
    Mocks\HttpRequestMock,
    Mocks\BibleHost,
    Mocks\BibleTestService;




/**
 * Tests for Route Manager
 */
class RoutesManagerTest extends \PHPUnit_Framework_TestCase {

  private $host;

  private $routes;

  public function setUp()
  {
      $this->host = new BibleHost();
      $this->host->init();
      $this->routes = $this->host->routes;
  }

  public function tearDown()
  {
    $this->host->dispose();
  }

  public function testBuildCanCacheExistingRoutes()
  {
    $pattern = RandomString::generate();
    $this->routes->add($pattern, BibleTestService::class, HttpRequestMock::class, array('GET'));
    $this->routes->build();
    $cached = $this->host->cacheProvider()->get(RoutesManager::CACHE_KEY);
    $this->assertNotNull($cached);
    $this->assertTrue(is_array($cached));
  }

  public function testBuildCanCacheExistingRoutes2()
  {
    // Build new RouteManager and confirm it load the cache at constructor
    $pattern = RandomString::generate();
    $routeManager = new RoutesManager($this->host);
    $routeManager->init();
    $routeManager->add($pattern, 'TestService', HttpRequestMock::class, array('GET'));
    $routeManager->build();
    $this->assertTrue(count($routeManager->routes) > 0);
  }

  public function testRegisterRoutesUsingAddAndGet()
  {
    $pattern = RandomString::generate();
    $this->routes->add($pattern, 'TestService', HttpRequestMock::class, array('GET'));
    $route = $this->routes->get($pattern);
    $this->assertNotNull($route);
    $route = $this->routes->get('/teste');
    $this->assertNull($route);
    $route = $this->routes->get($pattern, 'GET');
    $this->assertNotNull($route);
    $route = $this->routes->get($pattern, 'POST');
    $this->assertNull($route);
    $route = $this->routes->get($pattern, 'DELETE');
    $this->assertNull($route);
  }
}