<?hh

use Mocks\OdmContainer;
use Pi\ServiceInterface\Data\UserFollowRepository;
use Pi\ServiceInterface\UserFollowBusiness;
use Pi\ServiceModel\Types\AppFeed;

class UserFollowBusinessTest extends \PHPUnit_Framework_TestCase {

	protected $userFollowBus;

	protected $userFollowRepo;

	public function setUp()
	{
		$container = OdmContainer::get();
		$this->userFollowRepo = $container->getRepository(new UserFollowRepository());
		$this->userFollowBus = new UserFollowBusiness($this->userFollowRepo);
		$container->autoWireService($this->userFollowBus);
	}

	public function testCanFollowUser()
	{
		$userId = new \MongoId();
		$appId = new \MongoId();
		$this->userFollowBus->follow($appId, $userId);

		$feeds = $this->userFollowRepo->get($appId, 22);
		$this->assertTrue(count($feeds) === 1);
	}

	public function testCanGetFollowingIds()
	{
		$userId = new \MongoId();
		$friendId = new \MongoId();

		$this->userFollowBus->follow($userId, $friendId);
		$ids = $this->userFollowBus->getFollowingIds($userId);

		$this->assertTrue(count($ids) === 1);
	}
}