<?hh

use Mocks\OdmContainer;
use Pi\ServiceInterface\Data\UserFriendRequestRepository;
use Pi\ServiceInterface\Data\UserFriendRepository;
use Pi\Auth\UserRepository;
use Pi\ServiceInterface\UserFriendBusiness;
use Pi\ServiceModel\UserDto;
use Pi\Auth\UserEntity;

class UserFriendBusinessTest extends \PHPUnit_Framework_TestCase {

	protected $userFriendBus;

	protected $userRepo;
	protected $userFriendRepo;
	protected $userFriendReqRepo;
	protected $unitWork;

	public function setUp()
	{
		$container = OdmContainer::get();
		$this->unitWork = $container->get('UnitWork');
		$this->userFriendRepo = $container->getRepository(new UserFriendRepository());
		$this->userFriendReqRepo = $container->getRepository(new UserFriendRequestRepository());
		$this->userRepo = $container->getRepository(new UserRepository());
		$this->userFriendBus = new UserFriendBusiness($this->userFriendRepo, $this->userFriendReqRepo, $this->userRepo);
	}

	public function testCanRequestFriendship()
	{
		$userId = new \MongoId();
		$appId = new \MongoId();
		$this->userFriendBus->request($appId, $userId);

		$feeds = $this->userFriendReqRepo->get($userId, 22);

		$this->assertTrue(count($feeds) === 1);
	}

	public function testCanGetFriendsRequests()
	{
		$userId = $this->createUser('aa')->id();
		$dumb = $this->createUser();
		$friendId = $this->createUser('bbasdasd')->id();

		$this->userFriendBus->request($userId, $friendId);
		$ids = $this->userFriendBus->getFriendsRequestIds($friendId);

		$this->assertTrue(count($ids) === 1);

		$friends = $this->userFriendBus->getFriendsRequests($friendId);

		$this->assertTrue(count($friends) === 1);
		$this->assertTrue($friends[0] instanceof UserDto);
	}

	public function testCanUnfollowFriend()
	{
		$userId = $this->createUser()->id();
		$friendId = $friendId = $this->createUser('bbasdasd')->id();
		$this->userFriendRepo->add($userId, $friendId);
		$this->userFriendRepo->add($friendId, $userId);
		$this->assertTrue($this->userFriendBus->isFriendOf($userId, $friendId));
	}

	public function testCanAcceptFriendsRequests()
	{
		$userId = $this->createUser('aa')->id();
		$dumb = $this->createUser();
		$friendId = $this->createUser('bbasdasd')->id();
		$req = $this->userFriendBus->getFriendsRequests($userId);
		$this->assertTrue(count($req) === 0);
		$this->userFriendBus->request($userId, $friendId);
		$ids = $this->userFriendBus->getFriendsIds($friendId);
		$this->assertTrue(count($ids) === 0);
		$req = $this->userFriendBus->getFriendsRequests($friendId);
		$this->assertTrue(count($req) === 1);
		$this->userFriendBus->accept($userId, $friendId);
		$ids = $this->userFriendBus->getFriendsIds($friendId);
		$this->assertTrue(count($ids) === 1);
		$req = $this->userFriendBus->getFriendsRequests($friendId);
		$this->assertTrue(count($req) === 0);

		$friends = $this->userFriendBus->getFriends($friendId);


		$this->assertTrue(count($friends) === 1);
		$this->assertTrue($friends[0] instanceof UserDto);
		
	}

	protected function createUser($name = 'test user')
	{
		$entity = new UserEntity();
		$entity->setDisplayName($name);
		$this->unitWork->persist($entity);
		$this->unitWork->commit();
		return $entity;
	}
}