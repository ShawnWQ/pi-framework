<?hh

namespace SpotEvents\ServiceInterface;

use Pi\HttpError,
    Pi\HttpResult,
    Pi\Service,
    Pi\Extensions,
    Pi\Common\ClassUtils,
    Pi\ServiceInterface\AbstractCreativeWorkService,
    SpotEvents\ServiceModel\EventCategoryDto,
    Pi\ServiceModel\GetEventCommentsRequest,
    Pi\ServiceModel\GetEventCommentsResponse,
    Pi\ServiceModel\PostEventCommentRequest,
    Pi\ServiceModel\PostEventCommentResponse,
    Pi\ServiceModel\GetEventLikesRequest,
    Pi\ServiceModel\GetEventLikesResponse,
    Pi\ServiceModel\Types\ArticleCategoryEmbed,
    SpotEvents\ServiceModel\RemoveEventRequest,
    SpotEvents\ServiceModel\RemoveEventResponse,
    Pi\ServiceModel\PostEventLikeRequest,
    Pi\ServiceModel\PostEventLikeResponse,
    Pi\ServiceModel\Types\FeedAction,
    SpotEvents\ServiceModel\Types\EventCategory,
    Pi\ServiceInterface\LikesProvider,
    Pi\ServiceInterface\UserFriendBusiness,
    Pi\ServiceInterface\UserFeedBusiness,
    SpotEvents\ServiceModel\EventState,
    SpotEvents\ServiceModel\CreatePaymentRequest,
    SpotEvents\ServiceModel\CreatePaymentResponse,
    SpotEvents\ServiceModel\GetPaymentRequest,
    SpotEvents\ServiceModel\GetPaymentResponse,
    SpotEvents\ServiceModel\CreateEvent,
    SpotEvents\ServiceModel\CreateEventResponse,
    SpotEvents\ServiceModel\FindEvent,
    SpotEvents\ServiceModel\FindEventResponse,
    SpotEvents\ServiceModel\UpdateEventRequest,
    SpotEvents\ServiceModel\GetEventAttendantRequest,
    SpotEvents\ServiceModel\GetEventAttendantResponse,
    SpotEvents\ServiceModel\GetEventAvatarCardRequest,
    SpotEvents\ServiceModel\PostEventCategory,
    SpotEvents\ServiceModel\PostEventCategorySaveResponse,
    SpotEvents\ServiceModel\GetEvent,
    SpotEvents\ServiceModel\GetEventResponse,
    SpotEvents\ServiceModel\CreateEventSubscription,
    SpotEvents\ServiceModel\CreateEventSubscriptionResponse,
    SpotEvents\ServiceModel\CreateEventAttendantRequest,
    SpotEvents\ServiceModel\CreateEventAttendantResponse,
    SpotEvents\ServiceModel\GetEventSubscriptionRequest,
    SpotEvents\ServiceModel\GetEventSubscriptionResponse,
    SpotEvents\ServiceModel\Types\EventEntity,
    SpotEvents\ServiceModel\Types\EventSubscription,
    SpotEvents\ServiceModel\Types\EventAttendant,
    SpotEvents\ServiceModel\Types\EventSubscriptionDto,
    SpotEvents\ServiceModel\DTO\EventDto,
    SpotEvents\ServiceInterface\Data\EventRepository,
    SpotEvents\ServiceInterface\Data\EventCategoryRepository,
    SpotEvents\ServiceInterface\Data\EventSportEntityRepository,
    SpotEvents\ServiceInterface\Data\EventSubscriptionRepository,
    SpotEvents\ServiceInterface\Data\EventAttendantRepository,
    SpotEvents\ServiceModel\PaymentReceiveRequest,
    SpotEvents\ServiceModel\EventPaymentReceiveRequest,
    SpotEvents\ServiceModel\FindEventCategoryRequest,
    SpotEvents\ServiceModel\FindEventCategoryResponse,
    SpotEvents\ServiceModel\GetEventCategoryRequest,
    SpotEvents\ServiceModel\GetEventCategoryResponse,
    SpotEvents\ServiceModel\PostEventCategoryRequest,
    SpotEvents\ServiceModel\PostEventCategoryResponse,
    SpotEvents\ServiceModel\RemoveEventCategoryRequest,
    SpotEvents\ServiceModel\RemoveEventCategoryResponse,
    SpotEvents\ServiceModel\PostEventState,
    SpotEvents\ServiceModel\PostEventStateResponse;




class EventsService extends Service {

    public EventRepository $eventRepository;

    public EventCategoryRepository $categoryRepo;

    public EventSubscriptionRepository $subscriptionRepo;

    public PaymentService $paymentService;

    public EventAttendantRepository $attendantRepo;

    public LikesProvider $likesProvider;

    public UserFriendBusiness $friendBus;

    public UserFeedBusiness $feedBus;

    public static function getArticleId(string $displayName)
    {
        $trimmed = trim($displayName);
        $replaced = str_replace(' ', '-', $trimmed);
        return strtolower($replaced);
    }

    public static function validateState($state = null) 
    {
        return is_int($state);
    }

    <<Request,Method('GET'),Route('/event-category/:id')>>
    public function getCategory(GetEventCategoryRequest $request)
    {
        $response = new GetEventCategoryResponse();
        $dto = $this->categoryRepo->queryBuilder('SpotEvents\ServiceModel\EventCategoryDto')
            ->hydrate()
            ->field('_id')->eq($request->getId())
            ->getQuery()
            ->getSingleResult();
        $response->setCategory($dto);
        return $response;
    }

    <<Request,Method('POST'),Route('/event-category')>>
    public function postCategory(PostEventCategoryRequest $request)
    {
        $response = new PostEventCategoryResponse();
        $dto = new EventCategoryDto();
        $entity = new EventCategory();

        if(!is_null($request->getParent())) {
            $parent = $this->categoryRepo->get($request->getParent());
            if(!is_null($parent)) {
                $n = ',' . $parent->getId() . ',';

                $path = AbstractCreativeWorkService::formatPath($parent->getId(), $parent->getPath());
                $entity->setPath($path);
            }
        }
                ClassUtils::mapDto($request, $entity);

        $id = self::getArticleId($request->getDisplayName());
        $entity->setId($id);

        $this->categoryRepo->insert($entity);

        ClassUtils::mapDto($entity, $dto);
        $response->setCategory($dto);

        return $response;
    }

    <<Request,Method('GET'),Route('/event-category')>>
    public function findCategory(FindEventCategoryRequest $request)
    {
        $query = $this->categoryRepo->queryBuilder('SpotEvents\ServiceModel\EventCategoryDto')
            ->find()
            ->hydrate()
            ->limit(100)
            ->skip($request->getSkip());


        $categoryId = $request->getCategoryId();
        if(!is_null($categoryId)){
            $query
                ->field('path')->eq(new \MongoRegex("/,$categoryId,/"));
        }

        $data = $query
            ->getQuery()
            ->execute();


        $response = new FindEventCategoryResponse();
        $response->setCategories($data);
        return $response;
    }

    <<Request,Method('POST'),Route('/event-category-remove/:id')>>
    public function removeCategory(RemoveEventCategoryRequest $request)
    {
        $this->categoryRepo->remove($request->getId());
        $response = new RemoveEventCategoryResponse();
        return $response;
    }

    <<Request,Method('POST'),Route('/event-subscription')>>
    public function createSubscription(CreateEventSubscription $request)
    {
        $subscription = new EventSubscription();
        ClassUtils::mapDto($request, $subscription);
        //$subscription->setPayment($paymentRes->getPayment());
        $subscription->entityId($request->eventId());
        //$subscription->setPaymentId($paymentRes->getPayment()->getId());
        $subscription->setAmount($request->price());
        $this->subscriptionRepo->insert($subscription);

        $r = $this->eventRepository
            ->queryBuilder()
            ->update()
            ->field('_id')->eq($request->eventId())
            ->field('subscriptionId')->set($subscription->id())
            ->field('subscriptionAmount')->set($subscription->getAmount())
            ->getQuery()
            ->execute();

        $dto = new EventSubscriptionDto();
        ClassUtils::mapDto($subscription, $dto);

        $response = new CreateEventSubscriptionResponse();
        $response->setSubscription($dto);
        $response->setSubscriptionId($subscription->id());
        return $response;
    }

    <<Request,Method('GET'),Route('/event-subscription/:subscriptionId')>>
    public function getSubscription(GetEventSubscriptionRequest $request)
    {
        $response = new GetEventSubscriptionResponse();
        $subscription = $this->subscriptionRepo->getAs($request->getSubscriptionId(), 'SpotEvents\ServiceModel\Types\EventSubscriptionDto');
        $response->setSubscription($subscription);
        //$reqPayment = new GetPaymentRequest();

        //$reqPayment->setId($subscription->getPaymentId());
        //$resPayment = $this->execute($reqPayment);
        //$response->setPayment($resPayment->getPaymentDto());
        return $response;
    }

    <<Subscriber>>
    public function handlePayment(EventPaymentReceiveRequest $request)
    {

    }

    <<Request, Method('GET'),Route('/event')>>
    public function find(FindEvent $request)
    {
        $req = new PaymentReceiveRequest();
        $this->appHost->eventManager()->dispatch('SpotEvents\ServiceModel\PaymentReceiveRequest', $req);
        $query = $this->eventRepository->queryBuilder('SpotEvents\ServiceModel\DTO\EventDto')
            ->find()
            ->limit($request->getLimit())
            ->skip($request->getSkip())
            ->hydrate();

        if($request->getUpcoming()) {

        }

        $categoryId = $request->getCategoryId();
        if(!is_null($categoryId)){
            $query
                ->field('categoryPath')->eq(new \MongoRegex("/,$categoryId,/"));
        }

        $events = $query->getQuery()
            ->execute();
          $response = new FindEventResponse();

          foreach($events as $event) {
            //$event->setLikesCount($this->likesProvider->count($event->id()));
          }
        $response->setEvents($events);

        return $response;
    }

    <<Request,Method('GET'),Route('/event/:eventId')>>
    public function get(GetEvent $request)
    {
        //$event = $this->eventRepository->get($request->getEventId());
        $event = $this->eventRepository
          ->queryBuilder()
          ->findAndUpdate()
          ->hydrate()
          ->field('_id')->eq($request->getEventId())
          ->field('viewsCounter')->incr(1)
          ->getQuery()
          ->getSingleResult();

        if(is_null($event)) {
            return HttpResult::notFound(sprintf(
                'The Event with id %s wasn\'t found', $request->getEventId()
            ));
        }
        $dto = new EventDto();
        ClassUtils::mapDto($event, $dto);

        $response = new GetEventResponse($dto);
        return $response;
    }

    <<Request,Method('POST'),Route('/event'),Auth>>
    public function create(CreateEvent $request)
    {
        $event = new EventEntity();
        ClassUtils::mapDto($request, $event);

        $author = $this->request()->author();
        $event->setAuthor($author);
        $event->setCommentsCount(0);
        $event->setLikesCount(0);
        $event->setViewsCounter(0);
        

        if(is_null($request->getTags()) || count($request->getTags()) === 0) {
          $event->setTags(array());
        }

        if(!EventsService::validateState($request->getState())) {
            $event->setState(EventState::Published);
        }

        switch ($event->getState()) {

            case EventState::Published:
                $published = is_null($request->getDatePublished())
                    ? new \DateTime('now')
                    : $request->getDatePublished();
                $event->setDatePublished($published);
                break;
        }

        if(isset($request->getCategoryId())) {
            $category = $this->categoryRepo->get($request->getCategoryId());
            if(!is_null($category)) {
                $path = AbstractCreativeWorkService::formatPath($category->getId(), $category->getPath());
                $event->setCategoryPath($path);
                $embed = new ArticleCategoryEmbed();
                ClassUtils::mapDto($category, $embed);
                $event->setCategory($embed);
            } else {
                throw new \Exception('category doesnt exists');
            }
        }

        $this->eventRepository->insert($event);

        $url = Extensions::validateInputUrl($request->getUrl())
            ? $request->getUrl()
            : Extensions::getUrl($this->appConfig(), 'event', $event->getId(), $request->title());

        $this->eventRepository->queryBuilder()
            ->update()
            ->field('id')->eq($event->getId())
            ->field('url')->set($url)
            ->getQuery()
            ->execute();

        $dto = new EventDto();
        ClassUtils::mapDto($event, $dto, true);

        $action = new FeedAction(
    			$this->request()->getUserId(),
    			new \DateTime('now'),
    			false,
    			'basic',
    			'normal',
            array('title' => $event->title(), 'thumbnailSrc' => $event->getImage(), 'id' => (string)$event->id()),
    			'event-new');

    		$action->setAuthor($this->request->author());
    		$this->feedBus->createPublic($action);


        $response = new CreateEventResponse();
        $response->event($dto);
        return $response;
    }

    <<Request,Method('POST'),Route('/event/:id'),Auth>>
    public function save(UpdateEventRequest $request)
    {
        //Extensions::assertId($request->id());
        
        $query = $this->eventRepository->queryBuilder()
            ->update()
            ->field('_id')->eq($request->id())        
            ->field('dateModified')->set(new \DateTime('now'))
            ->field('title')->set($request->title())
            ->field('excerpt')->set($request->excerpt())
            ->field('content')->set($request->content());

        if(!is_null($request->doorTime())) {
            $query->field('doorTime')->set($request->doorTime());
        }
        if(!is_null($request->endDate())) {
            $query->field('endDate')->set($request->endDate());
        }
        
        $query->getQuery()
            ->execute();

        $response = new CreateEventResponse();
        //$response->event($dto);

        return $response;
    }

    <<Request,Method('GET'),Route('/event-card/:eventId')>>
    public function getCard(GetEventAvatarCardRequest $request)
    {
        $dest = imagecreatefrompng('http://fitting.pt/dist/images/fitting_logo.png');
        $res = $this->eventRepository->queryBuilder()
            ->find()
            ->hydrate(false)
            ->field('_id')->eq($request->getEventId())
            ->select('cardGen')
            ->getQuery()
            ->getSingleResult();

        $src = is_null($res) ? imagecreatefromjpeg('https://www.gravatar.com/avatar/06407bae4ac071e63df00ec6e34ed5ac?s=32&d=identicon&r=PG')
            : $res['cardGen'];

        imagecopymerge($dest, $src, 10, 9, 0, 0, 600, 400, 400); //have to play with these numbers for it to work for you, etc.

        header('Content-Type: image/png');
        imagepng($dest);

        imagedestroy($dest);
        imagedestroy($src);
        die();
    }

    <<Request,Method('GET'),Route('/event-attend/:eventId')>>
    public function getAttenders(GetEventAttendantRequest $request)
    {

        $response = new GetEventAttendantResponse();

        $attendant = $this->attendantRepo->get($request->getEventId());

        $response->setUsers($attendant);
        return $response;
    }

    <<Request,Method('POST'),Route('/event-like/:eventId')>>
    public function like(PostEventLikeRequest $request)
    {
        $response = new PostEventLikeResponse();
        //$this->likesProvider->add($request->getEventId(), $this->request()->getUserId());

        $friends = $this->friendBus->getFriendsIds($this->request()->getUserId());
        $event = $this->eventRepository->get($request->getEventId());

        $action = new FeedAction(
    			$this->request()->getUserId(),
    			new \DateTime('now'),
    			false,
    			'basic',
    			'normal',
    			array('user' => array('user' => $this->request()->author()), 'event' =>
            array('title' => $event->title(), 'thumbnailSrc' => $event->getThumbnailSrc(), 'id' => (string)$request->getEventId())),
    			'like-event');

    		$action->setAuthor($this->request->author());
    		$this->feedBus->createAll($friends, $action);
        return $response;
    }

    <<Request,Method('GET'),Route('/event-like/:eventId')>>
    public function findLikes(GetEventLikesRequest $request)
    {
        $response = new GetEventLikesResponse();
        $likes = $this->likesProvider->get($request->getEventId());
        $response->setLikes($likes);
        return $response;
    }

    <<Request,Method('POST'),Route('/event-attend')>>
    public function attend(CreateEventAttendantRequest $request)
    {

        $subscription = $this->subscriptionRepo->getByEntityId($request->getEventId());

        $paymentReq = new CreatePaymentRequest();
        $paymentReq->setAmount($subscription->getAmount());
        $paymentReq->setEntity($request->getEventId());
        $paymentRes = $this->execute($paymentReq);

        $response = new CreateEventAttendantResponse();
        $user = new EventAttendant();
        $user->setName('test');
        $user->setReference($paymentRes->getPayment()->getReference());
        $user->id($this->request()->getUserId());

        $this->attendantRepo->add($request->getEventId(), $user);

        $response->setPayment($paymentRes->getPayment());

        $friends = $this->friendBus->getFriendsIds($this->request()->getUserId());
        $event = $this->eventRepository->get($request->getEventId());
        $action = new FeedAction(
    			$this->request()->getUserId(),
    			new \DateTime('now'),
    			false,
    			'basic',
    			'normal',
    			array('user' => array('user' => $this->request()->author()), 'event' =>
            array('title' => $event->title(), 'thumbnailSrc' => $event->getThumbnailSrc(), 'id' => (string)$request->getEventId())),
    			'attend-event');

    		$action->setAuthor($this->request->author());
    		$this->feedBus->createAll($friends, $action);

        return $response;
    }

     <<Request,Method('POST'),Route('/event-remove/:id')>>
    public function remove(RemoveEventRequest $request)
    {
      $this->eventRepository->remove($request->getId());
      $response = new RemoveEventResponse();
      return $response;
    }

    <<Request,Method('POST'),Route('/event-state/:id')>>
    public function changeState(PostEventState $req)
    {
        $this->eventRepository->queryBuilder()
            ->update()
            ->field('_id')->eq($req->getId())
            ->field('state')->set($req->getState())
            ->field('dateModified')->set(new \DateTime('now'))
            ->getQuery()
            ->execute();


        $res = new PostEventStateResponse();
        return $res;
    }

    <<Request,Method('POST'),Route('/event-save-category/:id')>>
    public function changeCategory(PostEventCategory $req)
    {
        $category = $this->categoryRepo->get($req->getCategoryId());
        $path = AbstractCreativeWorkService::formatPath($category->getId(), $category->getPath());
        $embed = new ArticleCategoryEmbed();
        
        ClassUtils::mapDto($category, $embed);
        $this->eventRepository->queryBuilder()
            ->update()
            ->field('_id')->eq($req->getId())
            ->field('categoryPath')->set($path)
            ->field('category')->set($embed->jsonSerialize())
            ->field('dateModified')->set(new \DateTime('now'))
            ->getQuery()
            ->execute();


        $res = new PostEventCategorySaveResponse();
        return $res;
    }

    public function cancelAttending()
    {

    }

  }
