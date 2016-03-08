<?hh

namespace Pi;

class PhpUnitUtils {

  /**
   * @url http://stackoverflow.com/questions/249664/best-practices-to-test-protected-methods-with-phpunit
   */
  public static function callMethod($obj, $name, array $args) {
      $class = new \ReflectionClass($obj);
      $method = $class->getMethod($name);
      $method->setAccessible(true);
      return $method->invokeArgs($obj, $args);
  }

  public static function getMethod($obj, $name) {
    $class = new \ReflectionClass($obj);
    $method = $class->getMethod($name);
    $method->setAccessible(true);
    return $method;
  }
}
