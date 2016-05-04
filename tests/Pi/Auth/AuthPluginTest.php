<?hh

use Mocks\BibleHost,
	Pi\Auth\AuthenticateFilter,
	Pi\Auth\Interfaces\IAuthRepository;




class AuthPluginTest extends \PHPUnit_Framework_TestCase {

  public function testFilterIsRegisteredAtCore()
  {
    $host = new BibleHost();
    $host->init();
    $this->assertTrue($host->requestFiltersClasses()->contains(AuthenticateFilter::class));
    $repo = $host->container()->get(IAuthRepository::class);
  }
}
