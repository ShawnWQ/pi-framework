<?hh

use Mocks\OdmContainer,
	Pi\ServiceInterface\Data\AppFeedRepository,
	Pi\ServiceInterface\ApplicationFeedBusiness,
	Pi\ServiceModel\Types\AppFeed,
	Pi\Common\RandomString;

/**
 * @description
 * Tests for Application Feed Business Test
 */
class ApplicationFeedBusinessTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var Pi\ServiceInterface\ApplicationFeedBusiness
	 */
	protected $feedBusiness;

	/**
	 * @var Pi\ServiceInterface\Data\AppFeedRepository;
	 */
	protected $feedRepo;

	public function setUp()
	{
		$container = OdmContainer::get();
		$this->feedRepo = $container->getRepository(new AppFeedRepository());
		$this->feedBusiness = new ApplicationFeedBusiness($this->feedRepo);
	}

	/**
	 * Create a new Feed and assert its returned when queried
	 */
	public function testCreateFeed()
	{
		$feed = new AppFeed();
		$feed->setText('mocked');
		$appId = new \MongoId();
		$this->feedBusiness->create($appId, $feed);

		$feeds = $this->feedRepo->get($appId, 22, 'appId');
		$this->assertTrue(count($feeds) === 1);		
	}

	/**
	 * Insert 200 feeds, then query by 21 paginating the results
	 */
	public function testCanGetFeedsPaginated()
	{
		$feed = new AppFeed();
		$appId = new \MongoId();

		for ($i=0; $i < 230; $i++) { 
			$feed = new AppFeed();
			$feed->setText(RandomString::generate());
			$this->feedBusiness->create($appId, $feed);
		}

		for($i = 0; $i < 5; $i++) {
			$n = $i == 0 ? 1 : $i;
			$feeds = $this->feedBusiness->get($appId, 0, $n);
			$expected = $i === 0 ? 30 : 50;
			$this->assertTrue(count($feeds) === $expected, count($feeds) . ' should be ' . $expected . ', failed at index ' . $i . ' and take ' . $n);
		}
		
		$this->assertTrue($feeds[0]->getText() != $next[0]->getText(), 'the feeds returned are the same'); // test that the results arent the same 
	}
}