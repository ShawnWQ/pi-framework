<?hh

use Pi\Route;

class RouteTest
  extends \PHPUnit_Framework_TestCase {

    public function setUp()
    {

    }

    public function testCanCreateAndMatch()
    {
      $route = new Route('/api/test', 'TestService', 'TestMethod', false, array('GET', 'POST'));

      $this->assertTrue($route->matches('/api/test'));
      $this->assertTrue($route->matches('/api/test', 'GET'));
      $this->assertTrue($route->matches('/api/test', 'POST'));
      $this->assertFalse($route->matches('/api/test/1', 'POST'));
      $this->assertFalse($route->matches('/api/test', 'DELETE'));
      $this->assertFalse($route->matches('/api/test', 'PUT'));
      $this->assertFalse($route->matches('/api/teste'));
    }

    public function testRouteParameters()
    {
      $route = new Route('/api/:id', 'TestService', 'TestMethod', false, array('GET', 'POST'));
      $this->assertTrue($route->matches('/api/1'));

      $route = new Route('/api/:id:name+', 'TestService', 'TestMethod', false, array('GET', 'POST'));
      $this->assertTrue($route->matches('/api/1?name=td'));
    }
  }
