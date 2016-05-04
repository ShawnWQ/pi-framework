<?hh

namespace Pi\ServiceInterface;

use Pi\Auth\UserRepository;
use Pi\ServiceInterface\Data\UserFollowRepository;
use Pi\ServiceInterface\Data\UserFollowersRepository;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\IContainable;

class UserFollowBusiness implements IContainable {
	
	public UserRepository $userRepo;

	public UserFollowersRepository $followersRepo;

	public function __construct(public UserFollowRepository $followRepo)
	{

	}
	
	public function ioc(IContainer $ioc)
	{

	}
	public function getFollowingIds(\MongoId $userId)
	{
		return $this->followRepo->get($userId);
	}

	public function getFollowersIds(\MongoId $userId)
	{
		return $this->followersRepo->get($userId);
	}

	public function getFollowers(\MongoId $userId)
	{
		$ids = $this->getFollowersIds($userId);
		if(count($ids) === 0) {
			return $ids;
		}

		$data = $this->userRepo
			->queryBuilder('Pi\ServiceModel\UserDto')
			->find()
			->hydrate()
			->field('_id')->in($ids)
			->getQuery()
			->execute();

		return $data;
		
	}

	public function getUsers(\MongoId $userId)
	{
		$ids = $this->getFollowingIds($userId);
		if(count($ids) === 0) {
			return $ids;
		}

		$data = $this->userRepo
			->queryBuilder('Pi\ServiceModel\UserDto')
			->find()
			->hydrate()
			->field('_id')->in($ids)
			->getQuery()
			->execute();

		return $data;
		
	}

	public function follow(\MongoId $userId, \MongoId $followingId)
	{
		$this->followRepo->add($userId, $followingId);
		$this->followersRepo->add($followingId, $userId);
	}

	public function unfollow(\MongoId $userId, \MongoId $unfollowingId)
	{

	}
}