<?hh

namespace Mocks;
use Pi\HostConfig;


class MockHostConfiguration{
  public static function get()
  {
    $configuration = new HostConfig();
    return $configuration;
  }
}
