<?hh

use Mocks\BibleHost;
use Pi\Auth\AuthenticateFilter;


class AuthPluginTest extends \PHPUnit_Framework_TestCase {

  public function testFilterIsRegisteredAtCore()
  {
    $host = new BibleHost();
    $host->init();
    $this->assertTrue($host->requestFiltersClasses()->contains(get_class(new AuthenticateFilter())));
  }
}
