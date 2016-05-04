<?hh

namespace Pi\ServiceInterface;

use Pi\Service,
    Pi\ServiceModel\PostPlaceRequest,
    Pi\ServiceModel\PostPlaceResponse,
    Pi\ServiceModel\GetPlaceRequest,
    Pi\ServiceModel\GetPlaceResponse,
    Pi\ServiceModel\FindPlaceRequest,
    Pi\ServiceModel\FindPlaceResponse,
    Pi\ServiceModel\PostPlaceLikeRequest,
    Pi\ServiceModel\PostPlaceLikeResponse,
    Pi\ServiceModel\GetPlaceLikesRequest,
    Pi\ServiceModel\GetPlaceLikesResponse,
    Pi\ServiceModel\PostPlaceCommentRequest,
    Pi\ServiceModel\PostPlaceOpeningHoursRequest,
    Pi\ServiceModel\PostPlaceOpeningHoursResponse,
    Pi\ServiceModel\PlaceDto,
    Pi\ServiceModel\Types\FeedAction,
    Pi\ServiceModel\Types\Place,
    Pi\ServiceModel\Types\GeoCoordinates,
    Pi\ServiceModel\Types\OpeningHoursSpecification,
    Pi\ServiceInterface\Data\PlaceRepository,
    Pi\Common\ClassUtils;





class PlaceService extends Service {

  public PlaceRepository $placeRepo;

  public UserFriendBusiness $friendBus;

  public UserFeedBusiness $feedBus;

  protected $openingHoursBusiness;

  protected function validateCoordinates($latitude, $longitude, $elevation = null)
  {
    return (trim($latitude,'0') == (float)$latitude) && (trim($longitude,'0') == (float)$longitude);
  }

  protected function getOpeningHoursBusiness() : OpeningHoursBusiness
  {
    if(is_null($this->openingHoursBusiness)) {
      $this->openingHoursBusiness = new OpeningHoursBusiness($this->placeRepo);
    }

    return $this->openingHoursBusiness;
  }


  <<Request,Method('POST'),Route('/place')>>
  public function create(PostPlaceRequest $request)
  {

    $entity = new Place();
    $dto = new PlaceDto();
    $geo = new GeoCoordinates();

    if($this->validateCoordinates($request->getLatitude(), $request->getLongitude(), $request->getElevation())) {
      $geo->setLatitude($request->getLatitude());
      $geo->setLongitude($request->getLongitude());
      $geo->setElevation($request->getElevation());
      $entity->setGeo($geo->jsonSerialize());  
    } else {

      $entity->setGeo(array(array()));
    }

    // Author
    $author = $this->request()->author();
    $entity->setAuthor($author);

    ClassUtils::mapDto($request, $entity);
    if(!is_array($request->getOpeningHours()) || count($request->getOpeningHours()) === 0) {
      $entity->setOpeningHours(array(array()));
    }

    $this->placeRepo->insert($entity);

    ClassUtils::mapDto($entity, $dto);

    $response = new PostPlaceResponse();
    $response->setPlace($dto);

    return $response;
  }

  <<Request,Method('POST'),Route('place-hours/:id')>>
  public function addOpeningHours(PostPlaceOpeningHoursRequest$request)
  {
    $business = $this->getOpeningHoursBusiness();
    $business->add($request);

    $response = new PostPlaceOpeningHoursResponse();
    return $response;
  }

  <<Request,Method('GET'),Route('/place/:id')>>
  public function get(GetPlaceRequest $request)
  {

    $dto = $this->placeRepo->getAs($request->getId(), 'Pi\ServiceModel\PlaceDto');

    $response = new GetPlaceResponse();
    $response->setPlace($dto);

    return $response;
  }

  <<Request,Method('GET'),Route('/place')>>
	public function find(FindPlaceRequest $request)
	{
		$data = $this->placeRepo->queryBuilder('Pi\ServiceModel\PlaceDto')
			->find()
			->hydrate()
			->getQuery()
			->execute();


		$response = new FindPlaceResponse();
		$response->setPlaces($data);
		return $response;
	}

  <<Subscriber('Pi\ServiceModel\PostCommentArgs')>>
  public function onNewComment(PostPlaceCommentRequest $request)
  {
    if($request->getNamespace() !== 'place') {
      return;
    }
    $friends = $this->friendBus->getFriendsIds($request->getAuthor()['_id']);
    $place = $this->placeRepo->get($request->getEntityId());
    $action = new FeedAction(
      $request->getAuthor()['_id'],
      new \DateTime('now'),
      false,
      'basic',
      'normal',
      array('user' => array('user' => $this->request()->author()), 'event' =>
        array('title' => $place->getName(), 'id' => (string)$request->getEntityId())),
      'comment-place');

    $action->setAuthor($this->request->author());
    $this->feedBus->createAll($friends, $action);
  }

   <<Request,Method('POST'),Route('/place-like/:placeId')>>
    public function like(PostPlaceLikeRequest $request)
    {
        $response = new PostPlaceLikeResponse();
        //$this->likesProvider->add($request->getPlaceId(), $this->request()->getUserId());

        $friends = $this->friendBus->getFriendsIds($this->request()->getUserId());
        $event = $this->eventRepository->get($request->getPlaceId());

        $action = new FeedAction(
          $this->request()->getUserId(),
          new \DateTime('now'),
          false,
          'basic',
          'normal',
          array('user' => array('user' => $this->request()->author()), 'event' =>
            array('title' => $event->title(), 'thumbnailSrc' => $event->getThumbnailSrc(), 'id' => (string)$request->getPlaceId())),
          'like-place');

        $action->setAuthor($this->request->author());
        $this->feedBus->createAll($friends, $action);
        return $response;
    }

    <<Request,Method('GET'),Route('/place-like/:placeId')>>
    public function findLikes(GetPlaceLikesRequest $request)
    {
        $response = new GetPlaceLikesResponse();
        $likes = $this->likesProvider->get($request->getPlaceId());
        $response->setLikes($likes);
        return $response;
    }
}
