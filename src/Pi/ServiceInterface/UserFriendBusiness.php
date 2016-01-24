<?hh

namespace Pi\ServiceInterface;

use Pi\ServiceInterface\Data\UserFriendRepository;
use Pi\ServiceInterface\Data\UserFriendRequestRepository;
use Pi\Auth\UserRepository;
use Pi\ServiceModel\UserDto;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\IContainable;

class UserFriendBusiness implements IContainable  {
	
	public function __construct(public UserFriendRepository $friendRepo,
			public UserFriendRequestRepository $friendReqRepo,
			public UserRepository $userRepo)
	{

	}

	public function ioc(IContainer $ioc)
	{

	}

	public function getFriendsRequests(\MongoId $userId)
	{
		$ids = $this->getFriendsRequestIds($userId);
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

	public function getFriends(\MongoId $userId)
	{
		$ids = $this->getFriendsIds($userId);
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

	public function getFriendsIds(\MongoId $userId)
	{
		return $this->friendRepo->get($userId);
	}

	public function getFriendsRequestIds(\MongoId $userId)
	{
		return $this->friendReqRepo->get($userId);
	}

	public function isFriendOf(\MongoId $userId, \MongoId $friendId)
	{
		$exists = $this->friendReqRepo->isAttending($userId, $friendId, true);
		if($exists) return true;

		$exists = $this->friendRepo->isAttending($userId, $friendId, true);
		if($exists) return true;

		return false;	
	}

	public function request(\MongoId $userId, \MongoId $followingId)
	{
		$this->friendReqRepo->add($followingId, $userId);
	}

	public function accept(\MongoId $userId, \MongoId $friendId)
	{
		$this->friendRepo->add($friendId, $userId);	
		$this->friendRepo->add($userId, $friendId);	
		$this->friendReqRepo->removeEntry($friendId, $userId, true);
		$this->friendReqRepo->removeEntry($userId, $friendId, true);
	}

	public function unfollow(\MongoId $userId, \MongoId $unfollowingId)
	{
		$this->friendReqRepo->removeEntry($userId, $unfollowingId, true);
		$this->friendReqRepo->removeEntry($unfollowingId, $userId, true);
	}
}