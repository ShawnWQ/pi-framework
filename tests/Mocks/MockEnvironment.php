<?hh

namespace Mocks;

class MockEnvironment {
  static function mock($uri = '/test')
  {
    $_SERVER['REQUEST_URI'] = $uri;
  }
}
