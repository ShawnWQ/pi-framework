<?hh
use Mocks\CollaborationMockHost;
use Mocks\MockHostProvider;
use Mocks\HttpRequestMock;
use Mocks\BibleHost;
use Pi\ServiceModel\Types\Author;
use Mocks\AuthMock;
use Collaboration\ServiceInterface\Data\MeetingRepository;
use Collaboration\ServiceInterface\MeetingService;
use Collaboration\ServiceModel\CreateMeeting;
use Collaboration\ServiceModel\PublishMeeting;

class MeetingServiceTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var MeetingRepository
	 */
	protected $meetingRepo;

	public function setUp()
	{
		$this->host = new CollaborationMockHost();
		AuthMock::mock();
		$this->host->init();
		$this->meetingRepo = $this->host->container()->get('Collaboration\ServiceInterface\Data\MeetingRepository');
	}

	public function testDependenciesAreInjected()
	{
		$service = $this->host->container()->getService(new MeetingService());

		$this->assertTrue(!is_null($service->meetingRepo) && $service->meetingRepo instanceof MeetingRepository);
	}

	public function testCanCreateEvent()
	{
		$req = new CreateMeeting();
		$req->title('The event title');
		$req->excerpt('<p>html <b>formated</b> excerpt</p>');
		$req->content('<p>html <b>formated</b> content</p>');
		$doorTime = new \DateTime('now');
		$doorTime->modify('+4 day');
		$req->doorTime($doorTime);
		$req->modalityId(new \MongoId());
		$req->duration(3600);
		$req->endDate($doorTime->modify('+1 hour'));

		$response = $this->host->execute($req, new HttpRequestMock($req));
		//$this->assertEquals($response->event()->getTitle(), $req->title());
	}

}
