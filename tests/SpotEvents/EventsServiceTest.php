<?hh
use Pi\Common\RandomString,
	SpotEvents\ServiceInterface\EventsService,
    SpotEvents\ServiceModel\Types\EventSportEntity,
    SpotEvents\ServiceModel\Types\EventSubscription,
    SpotEvents\ServiceModel\Types\EventStatusType,
    SpotEvents\ServiceModel\Types\EventEntity,
    SpotEvents\ServiceModel\Types\EventAttendant,
    SpotEvents\ServiceModel\GetEvent,
    SpotEvents\ServiceModel\GetEventResponse,
    SpotEvents\ServiceModel\GetEventSubscriptionRequest,
    SpotEvents\ServiceModel\GetEventSubscriptionResponse,
    SpotEvents\ServiceModel\GetPaymentRequest,
    SpotEvents\ServiceModel\GetPaymentResponse,
    SpotEvents\ServiceModel\FindEvent,
    SpotEvents\ServiceModel\FindEventResponse,
    SpotEvents\ServiceModel\CreateEventAttendantRequest,
    SpotEvents\ServiceModel\CreateEventAttendantResponse,
    SpotEvents\ServiceModel\CreateEvent,
    SpotEvents\ServiceModel\CreateEventResponse,
    SpotEvents\ServiceModel\CreateEventSubscription,
    SpotEvents\ServiceModel\CreateEventSubscriptionResponse,
    SpotEvents\ServiceModel\GetEventAttendantRequest,
    SpotEvents\ServiceModel\GetEventAttendantResponse,
    SpotEvents\ServiceModel\UpdateEventRequest,
    SpotEvents\ServiceModel\UpdateEventResponse,
    Mocks\SpotEventMockHost,
    SpotEvents\ServiceInterface\Data\EventRepository,
    SpotEvents\ServiceInterface\Data\EventAttendantRepository,
    Mocks\MockHostProvider,
    Mocks\HttpRequestMock,
    Mocks\BibleHost,
    Pi\ServiceModel\Types\Author,
    Mocks\AuthMock;

class EventsServiceTest extends \PHPUnit_Framework_TestCase {

	protected $host;

	protected $eventRepository;

	/**
	 * @var EventAttendantRepository
	 */
	protected $attendantRepo;

	public function setUp()
	{
		$this->host = new SpotEventMockHost();
		AuthMock::mock();
		$this->host->init();

		$tmp = __DIR__ .'/../tmp';
		$this->host->config()->configsPath($tmp);
		$this->host->config()->cacheFolder($tmp);
		$this->eventRepository = $this->host->container()->get('SpotEvents\ServiceInterface\Data\EventRepository');
		$this->attendantRepo = $this->host->container()->get('SpotEvents\ServiceInterface\Data\EventAttendantRepository');
	}

	public function testDependenciesAreInjected()
	{
		$service = $this->host->container()->getService(new EventsService());

		$this->assertTrue(!is_null($service->eventRepository) && $service->eventRepository instanceof EventRepository);
	}

	

	public function testCanCreateEvent()
	{
		$req = new CreateEvent();
		$req->title('The event title');
		$req->excerpt('<p>html <b>formated</b> excerpt</p>');
		$req->content('<p>html <b>formated</b> content</p>');
		$doorTime = new \DateTime('now');
		$doorTime->modify('+4 day');
		$req->doorTime($doorTime);
		$req->modalityId(new \MongoId());
		$req->duration(3600);
		$req->endDate($doorTime->modify('+1 hour'));

		$response = $this->host->execute($req, new HttpRequestMock($req));
		$this->assertEquals($response->event()->getTitle(), $req->title());
	}


	public function testCreateEventAndAuthorIsSet()
	{
		$req = new CreateEvent();
		$req->title('The event title');
		$req->excerpt('<p>html <b>formated</b> excerpt</p>');
		$req->content('<p>html <b>formated</b> content</p>');
		$doorTime = new \DateTime('now');
		$doorTime->modify('+4 day');
		$req->doorTime($doorTime);
		$req->modalityId(new \MongoId());
		$req->duration(3600);
		$req->endDate($doorTime->modify('+1 hour'));

		$response = $this->host->execute($req, new HttpRequestMock($req));
		$eventDb = $this->eventRepository->get($response->event()->id());
		$this->assertEquals(new \MongoId($eventDb->getAuthor()->id()), AuthMock::getUser()->id());
	}

	public function testUpdateEvent()
	{
		$event = $this->createEvent();
		$req = new UpdateEventRequest();
		$req->id($event->id());

		$req->title(RandomString::generate());
		$req->excerpt($event->excerpt());
		$req->content($event->content());

		$res = $this->host->execute($req, new HttpRequestMock($req));
		$eventDb = $this->eventRepository->get($event->id());
		$this->assertEquals($eventDb->title(), $req->title());
		$this->assertEquals($event->excerpt(), $req->excerpt());
	}

	public function testGetEvent()
	{
		$entity = $this->createEvent();
		$req = new GetEvent($entity->id());
		$response = $this->host->execute($req, new HttpRequestMock($req));
		$this->assertTrue($response instanceof GetEventResponse);
		$this->assertEquals($response->getEvent()->getTitle(), $entity->title());
	}

	public function testFindEvents()
	{
		$this->createEvent();
		$request = new FindEvent();
		$response = MockHostProvider::execute($request);

		$this->assertTrue($response instanceof FindEventResponse);

		$this->assertTrue(count($response->getEvents()) > 0);
	}

	public function testCreateSubscription()
	{
		$event = $this->createEvent();
		$req = $this->createEventSubscriptionReq($event->id());
		$response = $this->host->execute($req, new HttpRequestMock($req));
		$this->assertTrue($response instanceof CreateEventSubscriptionResponse);

		$event = $this->eventRepository->get($event->id());
		$this->assertEquals($event->subscriptionId(), $response->getSubscriptionId());
		$this->assertEquals($event->id(), $response->getSubscription()->getEntityId());
	}


	public function testGetSubscription()
	{
		$event = $this->createEvent();
		$req = $this->createEventSubscriptionReq($event->id());
		$res = $this->host->execute($req, new HttpRequestMock($req));


		$request = new GetEventSubscriptionRequest();
		$request->setSubscriptionId($res->getSubscriptionId());

		$response = MockHostProvider::execute($request);
		$this->assertTrue($response instanceof GetEventSubscriptionResponse);
		//$this->assertTrue($response->getSubscription()->paymentId() !== null);
		$this->assertEquals($response->getSubscription()->getEntityId(), $event->id());
	}

	public function testCanAttendEvent()
	{
		$event = $this->createEvent();
		$subReq = $this->createEventSubscriptionReq($event->id());
		$subRes = MockHostProvider::execute($subReq);
		$request = new CreateEventAttendantRequest();
		$request->setEventId($event->id());

	    $res = $this->attendantRepo->isAttending($event->id(), $event->getAuthor()->getId());
	    $this->assertFalse($res);

		$response = MockHostProvider::execute($request);
		$this->assertTrue($response instanceof CreateEventAttendantResponse);

		$attendants = $this->attendantRepo->get($event->id());
		$this->assertTrue(count($attendants) === 1);

		// Redis counter
		$count = $this->attendantRepo->count($event->id());
		$this->assertTrue($count == 1);

		$paymentReq = new GetPaymentRequest();
		$paymentReq->setReference($response->getPayment()->getReference());

		$paymentRes = MockHostProvider::execute($paymentReq);

		$this->assertEquals($paymentRes->getPaymentDto()->getAmount(), $subReq->price());

	    $res = $this->attendantRepo->isAttending($event->id(), $event->getAuthor()->getId());
	    $this->assertTrue($res);

	}

	public function testGetAttendants()
	{

		$event = $this->createEvent();
		$request = new GetEventAttendantRequest();
		$request->setEventId($event->id());

		$response = MockHostProvider::execute($request);
		$this->assertTrue($response instanceof GetEventAttendantResponse);
		$this->assertTrue(count($response->getUsers()) === 0);

		for ($i = 1; $i <= 2; $i++) {

			$user = new EventAttendant();
	        $user->setName('test');
	        $user->id(new \MongoId());
	        $this->attendantRepo->add($request->getEventId(), $user);

	        $response = MockHostProvider::execute($request);

	        $this->assertEquals(count($response->getUsers()), $i);
		}
	}

	protected function createEventSubscriptionReq(?\MongoId $eventId = null)
	{
		if($eventId === null) $eventId = $this->createEvent()->id();
		$req = new CreateEventSubscription();
		$req->description('test');
		$req->eventId($eventId);
		return $req;
	}

	protected function createEvent() : EventEntity
	{
		$entity = new EventEntity();
		$entity->title('The event title');
		$entity->excerpt('<p>html <b>formated</b> excerpt</p>');
		$entity->content('<p>html <b>formated</b> content</p>');
		$doorTime = new \DateTime('now');
		$doorTime->modify('+4 day');
		$entity->doorTime($doorTime);
		$entity->modalityId(new \MongoId());
		$entity->duration(3600);
		$entity->endDate($doorTime->modify('+1 hour'));
		$author = new Author();
		$author->id(new \MongoId());
		$author->setDisplayName('test');
		$entity->setAuthor($author);
		$this->eventRepository->insert($entity);

		return $entity;
	}
}
