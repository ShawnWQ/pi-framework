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
use Pi\ServiceModel\FindUser;
use Pi\ServiceModel\FindUserResponse;
use Pi\ServiceModel\PostRequestFriendship;
use Pi\ServiceModel\PostAcceptFriend;
use Pi\ServiceModel\PostUserAddress;
use Pi\ServiceModel\UserContact;
use Pi\ServiceModel\UserInfo;

class FindUserServiceTest extends \PHPUnit_Framework_TestCase {

	protected $userRepo;

	public function setUp()
	{
		$container = OdmContainer::get();
		$this->userRepo = $container->get('Pi\Auth\UserRepository');
	}

	public function testCanFindNormal()
	{
		$user = $this->createUser();
		$req = new FindUser();

		$res = MockHostProvider::execute($req);
    $this->assertTrue(count($res->getUsers()) > 0);
	}


	protected function createUser($name = 'test user')
	{
		$entity = new UserEntity();
		$entity->setDisplayName($name);
		$this->userRepo->insert($entity);
		return $entity;
	}
}
