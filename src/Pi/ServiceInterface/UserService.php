<?hh

namespace Pi\ServiceInterface;

use Pi\Service;
use Pi\HttpResult;
use Pi\Common\RandomString;
use Pi\FileSystem\FileSystemConfiguration;
use Pi\ServiceInterface\Events\UserNewFrindshipRequest;
use Pi\ServiceInterface\Events\NewFriendshipArgs;
use Pi\Auth\UserRepository;
use Pi\ServiceInterface\Data\AlbumRepository;
use Pi\ServiceModel\UserDto;
use Pi\ServiceModel\GetUserFeedRequest;
use Pi\ServiceModel\GetUserFeedResponse;
use Pi\ServiceModel\PostRequestFriendship;
use Pi\ServiceModel\PostRequestFriendshipResponse;
use Pi\ServiceModel\RemoveFriendRequest;
use Pi\ServiceModel\RemoveFriendResponse;
use Pi\ServiceModel\PostFollowUser;
use Pi\ServiceModel\PostAlbumImage;
use Pi\ServiceModel\PostAlbumImageResponse;
use Pi\ServiceModel\PostFollowUserResponse;
use Pi\ServiceModel\UserProfileAvatarUpload;
use Pi\ServiceModel\GetUserAvatar;
use Pi\ServiceModel\PostAcceptFriend;
use Pi\ServiceModel\PostAcceptFriendResponse;
use Pi\ServiceModel\GetFriendsRequestRequest;
use Pi\ServiceModel\GetFriendsRequestResponse;
use Pi\ServiceModel\GetFriendsRequest;
use Pi\ServiceModel\GetFriendsResponse;
use Pi\ServiceModel\GetFollowingRequest;
use Pi\ServiceModel\GetFollowingResponse;
use Pi\ServiceModel\GetFriendStatusRequest;
use Pi\ServiceModel\GetFriendStatusResponse;
use Pi\ServiceModel\GetFollowersRequest;
use Pi\ServiceModel\GetFollowersResponse;
use Pi\ServiceModel\PostUserAddress;
use Pi\ServiceModel\BasicRegisterRequest;
use Pi\ServiceModel\PostSetup;
use Pi\ServiceModel\UserContact;
use Pi\ServiceModel\UserInfo;
use Pi\ServiceModel\Types\FeedAction;
use Pi\ServiceModel\Types\FriendCommonFeed;

class UserService extends Service {

	public AlbumRepository $albumRepository;
	public UserFriendBusiness $friendBus;
	public UserFollowBusiness $followBus;
	public UserFeedBusiness $userFeedBus;
	public UserRepository $userRepo;
	public FileSystemConfiguration $config;

	public function __construct()
	{
		parent::__construct();
	}

	<<Request,Method('GET'),Route('/user-avatar/:id')>>
	public function getAvatar(GetUserAvatar $request)
	{

	}

	<<Request,Method('POST'),Route('/user-avatar/:id')>>
	public function uploadAvatar(UserProfileAvatarUpload $request)
	{
		$fileNameToken = RandomString::generate(20);
		$tokenId = new \MongoId();

		$fileId = new \MongoId();
		$userId = (string)$this->request()->getUserId();
		$fileName =  $userId;
		$savePath = $this->config->storeDir() . '/user/' . $fileName;

		move_uploaded_file($request->file()->tmpName(), $savePath);
		$path = 'http://' . $this->appHost()->config()->domain() . '/user/' . $fileName;
		$this->addAvatarToAlbum($savePath);
	}

	protected function addAvatarToAlbum(string $imageSrc, ?string $description = null)
	{
		$req = new PostAlbumImage();
		$req->setImageSrc($imageSrc);
		if(!is_null($description)) {
			$req->setText($description);
		}

		$albumId = $this->albumRepository->getProfileAlbumId($this->request->getUserId());
		if(is_null($albumId)) {
			throw new \Exception(sprintf('User with id %s doesnt have a profile album created', $this->request()->getUserId()));
		}
		$req->setAlbumId($albumId);
		$res = $this->execute($req);
	}

	<<Request,Method('POST'),Route('/user-follow')>>
	public function folow(PostFollowUser $request)
	{
		$response = new PostFollowUserResponse();
		$this->followBus->follow($this->request()->getUserId(), $request->getUserId());
		return $response;
	}

	<<Request,Method('GET'),Route('/friends-status/:userId')>>
	public function isFriend(GetFriendStatusRequest $request)
	{
		$response = new GetFriendStatusResponse();
		$isFriend = $this->friendBus->isFriendOf($this->request()->getUserId(), $request->getUserId());
		$response->setIsFriend($isFriend);
		return $response;
	}

	<<Request,Method('GET'),Route('/user-follow/:userId')>>
	public function getFollowing(GetFollowingRequest $request)
	{
		$response = new GetFollowingResponse();
		$users = $this->followBus->getUsers($request->getUserId());
		$response->setFollowing($users);
		return $response;
	}

	<<Request,Method('GET'),Route('/user-followers/:userId')>>
	public function getFollowers(GetFollowersRequest $request)
	{
		$response = new GetFollowersResponse();
		$users = $this->followBus->getFollowers($request->getUserId());
		$response->setFollowing($users);
		return $response;
	}

	<<Request,Method('GET'),Route('/friends/:userId')>>
	public function getFriends(GetFriendsRequest $request)
	{
		$response = new GetFriendsResponse();
		$friends = $this->friendBus->getFriends($request->getUserId());
		$response->setFriends($friends);
		return $response;
	}

	<<Request,Method('GET'),Route('/feed/:userId')>>
	public function getFeed(GetUserFeedRequest $request)
	{
		$response = new GetUserFeedResponse();

		$feeds = $this->userFeedBus->get($request->getUserId());
		$response->setFeeds($feeds);
		return $response;
	}

	<<Request,Method('GET'),Route('/friends-requests/:userId')>>
	public function getFriendsRequests(GetFriendsRequestRequest $request)
	{
		$response = new GetFriendsRequestResponse();

		$friends = $this->friendBus->getFriendsRequests($request->getUserId());
		$response->setFriends($friends);
		return $response;
	}

	<<Request,Method('POST'),Route('/friends'),Auth>>
	public function requestFriend(PostRequestFriendship $request)
	{
		$response = new PostRequestFriendshipResponse();
		$this->friendBus->request($this->request()->getUserId(), $request->getUserId());
		$user = $this->userRepo->getAs($request->getUserId(), 'Pi\ServiceModel\UserDto');
		$response->setRequested($user);
		return $response;
	}

	<<Request,Method('POST'),Route('/friends-accept'),Auth>>
	public function acceptFriend(PostAcceptFriend $request)
	{
		$response = new PostAcceptFriendResponse();
		$this->friendBus->accept($this->request()->getUserId(), $request->getUserId());

		$dto = new UserDto();
		$dto->id($this->request()->getUserId());
		$dto->setDisplayName($this->request()->getAuthor()->getDisplayName());
		$friend = $this->userRepo->getAs($request->getUserId(), 'Pi\ServiceModel\UserDto');
		$args = new NewFriendshipArgs($dto, $friend);

		$this->eventManager()->dispatch('Pi\ServiceInterface\Events\NewFriendshipArgs', $args);
		return $response;
	}

	<<Request,Method('POST'),Route('/friend-unfollow')>>
	public function removeFriend(RemoveFriendRequest $request)
	{

		$this->friendBus->unfollow($this->request()->getUserId(), $request->getUserId());
		$response = new RemoveFriendResponse();
		return $response;
	}

	<<Subscriber('Pi\ServiceInterface\Events\NewFriendshipArgs')>>
	public function onNewFriendInComon(UserNewFrindshipRequest $request)
	{

		$id = $request->getUser()->id();
		$friends = $this->friendBus->getFriendsIds($id);
		$action = new FeedAction(
			$this->request()->getUserId(),
			new \DateTime('now'),
			false,
			'basic',
			'normal',
			array('user' => array('id' => (string)$request->getUser()->id(), 'displayName' => $request->getUser()->getDisplayName()), 'friend' => array('id' => (string)$request->getFriend()->id(), 'displayName' => $request->getFriend()->getDisplayName())),
			'friendship');

		$action->setAuthor($this->request->author());
		$this->userFeedBus->createAll($friends, $action);
	}

	<<Request,Method('POST'),Route('/user-address/:id')>>
	public function postAddress(PostUserAddress $request)
	{
		$this->userRepo->queryBuilder()
			->update()
			->upsert()
			->field('_id')->eq($request->getId())
			->field('address.cep')->set($request->getCep())
			->field('address.city')->set($request->getCity())
			->field('address.country')->set($request->getCountry())
			->field('address.address')->set($request->getAddress())
			->getQuery()
			->execute();
	}

	<<Request,Method('GET'),Route('/user-address/:id')>>
	public function getAddress(PostUserAddress $request)
	{

		$data = $this->userRepo->queryBuilder()
			->find()
			->hydrate()
			->select('_id', 'address')
			->field('_id')->eq($request->getId())
			->getQuery()
			->getSingleResult();

		if(is_null($data)) {
			return HttpResult::notFound();
		}

		return $data->getAddress();
	}

	<<Request,Method('POST'),Route('/user-address/:id')>>
	public function postContact(UserContact $request)
	{
		$this->userRepo->queryBuilder()
			->update()
			->upsert()
			->field('_id')->eq($request->getId())
			->field('contact.phone')->set($request->getPhone())
			->field('address.mobile')->set($request->getMobile())
			->field('address.emailPublic')->set($request->getEmailPublic())
			->getQuery()
			->execute();
	}

	<<Request,Method('GET'),Route('/user-address/:id')>>
	public function getContact(UserContact $request)
	{

		$data = $this->userRepo->queryBuilder()
			->find()
			->hydrate()
			->select('_id', 'contact')
			->field('_id')->eq($request->getId())
			->getQuery()
			->getSingleResult();

		if(is_null($data)) {
			return HttpResult::notFound();
		}

		return $data->getContact();
	}

	<<Request,Method('POST'),Route('/user-info/:id')>>
	public function postInfo(UserInfo $request)
	{
		$this->userRepo->queryBuilder()
			->update()
			->upsert()
			->field('_id')->eq($request->getId())
			->field('firstName')->set($request->getFirstName())
			->field('lastName')->set($request->getLastName())
			->field('displayName')->set($request->getDisplayName())
			->getQuery()
			->execute();
	}

	<<Request,Method('GET'),Route('/user-info/:id')>>
	public function getInfo(UserInfo $request)
	{

		$data = $this->userRepo->queryBuilder()
			->find()
			->hydrate()
			->select('_id', 'firstName', 'lastName', 'displayName')
			->field('_id')->eq($request->getId())
			->getQuery()
			->getSingleResult();

		if(is_null($data)) {
			return HttpResult::notFound();
		}

		return $data;
	}

	<<Request,Method('GET'),Route('/setup')>>
	public function setup(PostSetup $request)
	{
		$req = new BasicRegisterRequest();
		$req->firstName('Guilherme');
		$req->lastName('Cardoso');
		$req->displayName('Guilherme Cardoso');
		$req->email('email@guilhermecardoso.pt');
		$req->password('123123');
		$req->setCity('Viseu');
		$res = $this->execute($req);
	}
}
