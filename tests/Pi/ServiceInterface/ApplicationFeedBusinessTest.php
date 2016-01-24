<?hh

use Mocks\OdmContainer;
use Pi\ServiceInterface\Data\AppFeedRepository;
use Pi\ServiceInterface\ApplicationFeedBusiness;
use Pi\ServiceModel\Types\AppFeed;

class ApplicationFeedBusinessTest extends \PHPUnit_Framework_TestCase {

	protected $feedBusiness;

	protected $feedRepo;

	public function setUp()
	{
		$container = OdmContainer::get();
		$this->feedRepo = $container->getRepository(new AppFeedRepository());
		$this->feedBusiness = new ApplicationFeedBusiness($this->feedRepo);
	}

	public function testCreateFeed()
	{
		$feed = new AppFeed();
		$feed->setText('mocked');
		$appId = new \MongoId();
		$this->feedBusiness->create($appId, $feed);

		$feeds = $this->feedRepo->get($appId, 22, 'appId');
		$this->assertTrue(count($feeds) === 1);
		
	}
}