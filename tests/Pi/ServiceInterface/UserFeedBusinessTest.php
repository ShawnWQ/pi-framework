<?hh

use Mocks\OdmContainer;
use Mocks\AuthMock;
use Pi\ServiceInterface\Data\FeedActionRepository;
use Pi\ServiceInterface\Data\UserFeedItemRepository;
use Pi\Auth\UserRepository;
use Pi\ServiceInterface\UserFeedBusiness;
use Pi\ServiceModel\Types\FeedAction;

class UserFeedBusinessTest extends \PHPUnit_Framework_TestCase {

	protected $feedBusiness;

	protected $feedRepo;

	protected $userFeedRepo;

	public function setUp()
	{
		$container = OdmContainer::get();
		$this->feedRepo = $container->getRepository(new FeedActionRepository());
		$this->userFeedRepo = $container->getRepository(new UserFeedItemRepository());
		$userRepo = $container->getRepository(new UserRepository());
		$this->feedBusiness = new UserFeedBusiness($this->feedRepo, $this->userFeedRepo, $userRepo);
	}

	public function testCreateFeed()
	{
		$user = AuthMock::mock();
		$feed = new FeedAction($user->id(),
			new \DateTime('now'),
			false,
			'basic',
			'normal',
			array('message' => 'test'));

		$this->feedBusiness->createAll(array($user->id()), $feed);
		$feeds = $this->feedBusiness->get($user->id());
		$this->assertTrue(count($feeds) > 0);
	}
	protected function createFeed()
	{

	}
}
