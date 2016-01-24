<?hh
use Mocks\CollaborationMockHost;
use Mocks\MockHostProvider;
use Mocks\HttpRequestMock;
use Mocks\BibleHost;
use Pi\ServiceModel\Types\Author;
use Mocks\AuthMock;
use Collaboration\ServiceInterface\Data\MeetingRepository;
use Collaboration\ServiceInterface\PageService;
use Collaboration\ServiceModel\PostPageRequest;
use Collaboration\ServiceModel\PostPageResponse;
use Collaboration\ServiceModel\GetPageRequest;
use Collaboration\ServiceModel\GetPageResponse;

class PageServiceTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var MeetingRepository
	 */
	protected $pageRepo;

	public function setUp()
	{
		$this->host = new CollaborationMockHost();
		AuthMock::mock();
		$this->host->init();
		$this->pageRepo = $this->host->container()->get('Collaboration\ServiceInterface\Data\PageRepository');
	}

	public function testDependenciesAreInjected()
	{
		$service = $this->host->container()->getService(new PageService());

		$this->assertTrue(!is_null($service->meetingRepo) && $service->meetingRepo instanceof MeetingRepository);
	}

	public function testCanCreatePage()
	{
		$req = new PostPageRequest();
		$req->setTitle('The event title');
		$req->setBody('<p>html <b>formated</b> excerpt</p>');

		$response = $this->host->execute($req, new HttpRequestMock($req));
		$this->assertEquals($response->getPage()->getTitle(), $req->getTitle());
	}
}
