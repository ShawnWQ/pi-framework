<?hh
use Mocks\MultiTenantHostMock;
use Mocks\MockHostProvider;

class MultiTenantHostTest extends \PHPUnit_Framework_TestCase {

	protected $host;

	protected $appRepo;

	public function setUp()
	{
		$_SERVER['REQUEST_URI'] = '/test';
		$this->host = new MultiTenantHostMock();

		$this->appRepo = $this->host->tryResolve('Pi\ServiceInterface\Data\ApplicationRepository');
		$this->appRepo->setRedisDomain('mock-id', 'localhost');

		$this->host->init();

		// Mock a random app and appId
	}

	public function testAppIdIsAssignedToIRequest()
	{
		$req = $this->host->tryResolve('IRequest');
		$this->assertTrue($req->appId() === 'mock-id');
	}
}
