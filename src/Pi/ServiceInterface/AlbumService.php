<?hh

namespace Pi\ServiceInterface;

use Pi\Service;
use Pi\ServiceInterface\Data\AlbumRepository;
use Pi\ServiceInterface\Data\AlbumImageRepository;
use Pi\Auth\UserRepository;
use Pi\ServiceModel\GetAlbumImageRequest;
use Pi\ServiceModel\GetAlbumImageResponse;
use Pi\ServiceModel\GetAlbunsRequest;
use Pi\ServiceModel\GetAlbunsResponse;
use Pi\ServiceModel\GetAlbumRequest;
use Pi\ServiceModel\GetAlbumResponse;
use Pi\ServiceModel\PostAlbumImage;
use Pi\ServiceModel\PostAlbumImageResponse;
use Pi\ServiceModel\PostAlbumRequest;
use Pi\ServiceModel\PostAlbumResponse;
use Pi\ServiceModel\AlbumDto;
use Pi\ServiceModel\AlbumImageDto;
use Pi\ServiceModel\Types\Album;
use Pi\ServiceModel\Types\FeedAction;
use Pi\ServiceModel\Types\AlbumImage;
use Pi\ServiceModel\AlbumType;
use Pi\ServiceModel\AlbumNewUserCreated;
use Pi\ServiceInterface\Events\NewUserRegisterArgs;
use Pi\Common\ClassUtils;
use Pi\EventSubscriber;
use Pi\Interfaces\IEventSubscriber;

class AlbumService extends Service implements IEventSubscriber {

	public AlbumRepository $albumRepo;

	public AlbumImageRepository $imageRepo;

	public UserFriendBusiness $friendBus;
	public UserFollowBusiness $followBus;
	public UserFeedBusiness $userFeedBus;
	public UserRepository $userRepo;

	public function getEventsSubscribed()
	{
		return array('onNewUserRegistered');
	}

	<<Subscriber('Pi\ServiceInterface\Events\NewUserRegisterArgs')>>
	public function onNewUserRegistered(NewUserRegisterArgs $request)
	{
		$id = $request->getUser()->id();
		$req = new PostAlbumRequest();
		$req->setType(AlbumType::Profile);
		$req->setUserId($id);
		$this->execute($req);
	}

	<<Request,Route('/album'),Method('POST')>>
	public function postAlbum(PostAlbumRequest $req)
	{
		$entity = new Album();
		ClassUtils::mapDto($req, $entity);
		$entity->setLastImages(array(array()));
		$userId = isset($req->getUserId()) ? $req->getUserId() : $this->request()->getUserId();
		$entity->setAuthorId($userId);
		if(is_null($req->getType())) {
			$entity->setType(AlbumType::Normal);
		}
		$this->albumRepo->insert($entity);

		$r = $this->userRepo->queryBuilder()
			->update()
			->upsert()
			->field('_id')->eq($userId)
			->field('albuns')->push($entity->id())
			->getQuery()
			->execute();



		$dto = new AlbumDto();
		ClassUtils::mapDto($entity, $dto);
		$response = new PostAlbumResponse();
		$response->setAlbum($dto);
		return $response;
	}

	<<Request,Route('/image'),Method('POST')>>
	public function postImage(PostAlbumImage $req)
	{
		$entity = new AlbumImage();
		ClassUtils::mapDto($req, $entity);
		$this->imageRepo->insert($entity);
		$q = $this->albumRepo->queryBuilder()
			->update()
			->field('_id')->eq($req->getAlbumId())
			->field('lastImages')->push($req->getImageSrc())
			->getQuery()
			->execute();

		$id = $this->request()->getUserId();
		$friends = $this->friendBus->getFriendsIds($id);
		$action = new FeedAction(
			$this->request()->getUserId(),
			new \DateTime('now'),
			false,
			'basic',
			'normal',
			array('id' => (string)$entity->id(), 'text' => $req->getText(), 'imageSrc' => $req->getImageSrc()),
			'photo');

		$action->setAuthor($this->request->author());
		$this->userFeedBus->createAll($friends, $action);
		$dto = new AlbumImageDto();
		ClassUtils::mapDto($entity, $dto);
		$response = new PostAlbumImageResponse();
		$response->setImage($dto);
		$response->setFeed($action);
		return $response;
	}

	<<Request,Route('/user/:userId/album'),Method('GET')>>
	public function getAlbums(GetAlbunsRequest $request)
	{
		$response = new GetAlbunsResponse();
		$query = $this->albumRepo
			->queryBuilder('Pi\ServiceModel\AlbumDto')
			->find()
			->hydrate();

		if(isset($request->getUserId())) {
			$query
				->field('authorId')->eq($request->getUserId());
		}
		$albuns = $query
			->getQuery()
			->execute();

		$response->setAlbuns($albuns);

		return $response;
	}

	<<Request,Route('/image/:imageId'),Method('GET')>>
	public function getImage(GetAlbumImageRequest $request)
	{
		$response = new GetAlbumImageResponse();
		$dto = $this->imageRepo->getAs($request->getImageId(), 'Pi\ServiceModel\AlbumImageDto');
		$response->setImage($dto);
		return $response;
	}

	<<Request,Route('/album/:albumId'),Method('GET')>>
	public function getImages(GetAlbumRequest $request)
	{
		$response = new GetAlbumResponse();

		$data = $this->imageRepo
			->queryBuilder('Pi\ServiceModel\AlbumImageDto')
			->field('albumId')->eq($request->getAlbumId())
			->find()
			->hydrate()
			->getQuery()
			->execute();

		$response->setImages($data);

		return $response;
	}
}
