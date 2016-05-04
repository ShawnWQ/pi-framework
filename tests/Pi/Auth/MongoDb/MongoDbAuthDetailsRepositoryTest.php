<?hh

use Mocks\BibleHost;
use Pi\Auth\AuthenticateFilter,
	Pi\Auth\MongoDb\MongoDbAuthRepository;


class MongoDbAuthDetailsRepositoryTest extends \PHPUnit_Framework_TestCase {

	protected $host;

	public function setUp()
	{
		$this->host = new BibleHost();
		$this->host->init();
	}

	public function testFilterIsRegisteredAtCore()
	{
		$this->assertTrue($this->host->container()->get('Pi\Auth\Interfaces\IAuthDetailsRepository') != null);
	}

	protected function getAuthUserRepository()
	{
		return $this->host->container()->get('Pi\Auth\Interfaces\IAuthUserRepository');
	}

	protected function getAuthRepository()
	{
		return $this->host->container()->get('Pi\Auth\Interfaces\IAuthRepository');
	}

	protected function getAuthDetailsRepository()
	{
		return $this->host->container()->get('Pi\Auth\Interfaces\IAuthDetailsRepository');
	}
}
