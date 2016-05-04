<?hh

use Mocks\OdmContainer;
use Mocks\MockHostProvider;
use Mocks\AuthMock;
use Pi\ServiceInterface\Data\UserFriendRequestRepository;
use Pi\ServiceInterface\Data\UserFriendRepository;
use Pi\Auth\UserRepository;
use Pi\ServiceInterface\UserFriendBusiness;
use Pi\ServiceModel\UserDto;
use Pi\Auth\UserEntity;
use Pi\ServiceModel\GetFriendsRequest;
use Pi\ServiceModel\GetFriendsResponse;
use Pi\ServiceModel\PostRequestFriendship;
use Pi\ServiceModel\PostAcceptFriend;
use Pi\ServiceModel\PostUserAddress;
use Pi\ServiceModel\UserContact;
use Pi\ServiceModel\UserInfo;

class UserServiceTest extends \PHPUnit_Framework_TestCase {

	protected $userRepo;

	public function setUp()
	{
		$container = OdmContainer::get();
		$this->userRepo = $container->get('Pi\Auth\UserRepository');
	}

	public function testCanSaveInfo()
	{
		$user = $this->createUser();
		$req = new UserInfo();
		$req->setFirstName('viseu');
		$req->setLastName('Portugal');
		$req->setDisplayName('test long address ras0');
		$req->setId($user->getId());
		$res = MockHostProvider::execute($req);
	}

	public function testCanSaveContact()
	{
		$user = $this->createUser();
		$req = new UserContact();
		$req->setPhone('viseu');
		$req->setMobile('Portugal');
		$req->setEmailPublic('test long address ras0');
		$req->setId($user->getId());
		$res = MockHostProvider::execute($req);

	}

	public function testCanSaveAddress()
	{
		$user = $this->createUser();
		$req = new PostUserAddress();
		$req->setCity('viseu');
		$req->setCountry('Portugal');
		$req->setAddress('test long address ras0');
		$req->setCep('6323');
		$req->setId($user->getId());
		$res = MockHostProvider::execute($req);

	}

	public function testAcceptFriend()
	{
		$user = $this->createUser('asdas');
		$friend = $this->createUser('123');
		AuthMock::mock($user);
		$req = new PostRequestFriendship();
		$req->setUserId($friend->id());
		MockHostProvider::execute($req);

		$req = new PostAcceptFriend();
		$req->setUserId($friend->id());
		MockHostProvider::execute($req);

	}

	protected function createUser($name = 'test user')
	{
		$entity = new UserEntity();
		$entity->setDisplayName($name);
		$this->userRepo->insert($entity);
		return $entity;
	}
}
