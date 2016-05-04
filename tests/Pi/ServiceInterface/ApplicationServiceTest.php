<?hh

use Pi\Auth\UserEntity,
	Pi\Validation\ValidationException,
	Pi\ServiceInterface\ApplicationService,
	Pi\ServiceModel\ApplicationChangeDomain,
	Pi\ServiceModel\ApplicationChangeDomainResponse,
	Pi\ServiceModel\ApplicationCreateRequest,
	Pi\ServiceModel\ApplicationCreateResponse,
	Pi\ServiceModel\ApplicationDto,
	Pi\ServiceModel\ApplicationGetRequest,
	Pi\ServiceModel\ApplicationGetResponse,
	Pi\ServiceModel\ApplicationFindRequest,
	Pi\ServiceModel\ApplicationFindResponse,
	Pi\ServiceModel\UpdateApplicationMailRequest,
	Pi\ServiceModel\UpdateApplicationMailResponse,
	Pi\ServiceModel\GetApplicationMailRequest,
	Pi\ServiceModel\GetApplicationMailResponse,
	Pi\ServiceModel\Types\Application,
	Pi\Common\RandomString,
	Mocks\MockHostProvider,
	Mocks\OdmContainer;

class ApplicationServiceTest extends \PHPUnit_Framework_TestCase{

	protected $userRepo;

	protected $appService;

	public function setUp()
	{
		$container = OdmContainer::get();
		$this->userRepository = $container->get('Pi\Auth\UserRepository');
		$this->appRepository = $container->get('Pi\ServiceInterface\Data\ApplicationRepository');
		$this->appService = $container->getService(new ApplicationService());	
	}

	public function testCanCreateApplication()
	{
		$user = $this->getUser();
		$req = new ApplicationCreateRequest('correct name', 'A briefe description about the application', RandomString::generate() . '.com', $user->id());
		$res = MockHostProvider::execute($req);

		$this->assertTrue($res instanceof ApplicationCreateResponse);
	}

	public function testCanFindApplications()
	{
		$req = new ApplicationFindRequest();

		$this->testCanCreateApplication();

		$res = MockHostProvider::execute($req);
		$this->assertTrue($res instanceof ApplicationFindResponse);
		$this->assertTrue(count($res->getApplications()) > 0);
	}

	public function testCanGetAppById()
	{
		$app = new Application();
		$app->setName('Gui App');
		$this->appRepository->insert($app);

		$request = new ApplicationGetRequest();
		$request->setAppId($app->getId());

		$response = MockHostProvider::execute($request);
		$this->assertTrue($response instanceof ApplicationGetResponse);
		$this->assertEquals($response->getApplication()->getName(), $app->getName());
	}

	public function testValidationForCreateApplication()
	{
		$user = $this->getUser();
		$req = new ApplicationCreateRequest();
		$throwed = false;
		
		try {
			MockHostProvider::execute($req);
		}
		catch(ValidationException $ex) {
			$throwed = true;
		}

		$this->assertTrue($throwed);
	}

	public function testCanChangeDomainAndReflectChangesInNginx()
	{
		$app = new Application();
		$host = RandomString::generate(20);
		$app->setDomain('localhost');

		$this->appRepository->insert($app);
		$this->appRepository->setRedisDomain($app->id(), 'localhost');

		$appId = $this->appRepository->getRedisByDomain('localhost');
		$this->assertEquals($appId, $app->id());

		$request = new ApplicationChangeDomain();
		$request->setAppId($app->id());
		$request->setNewDomain($host);

		$response = MockHostProvider::execute($request);
		$this->assertTrue($response instanceof ApplicationChangeDomainResponse);

		$appId = $this->appRepository->getRedisByDomain($host);
		$this->assertEquals($appId, $app->id());

		$appRefresh = $this->appRepository->get($app->id());
		$this->assertEquals($appRefresh->getDomain(), $host);

	}

	public function testCanUpdateMailSettings()
	{
		$app = $this->createApp();
		$request = new UpdateApplicationMailRequest();
		$request->setHeader(RandomString::generate());
		$request->setFooter(RandomString::generate());
		$request->setAppId($app);

		$response = MockHostProvider::execute($request);

	}

	public function testCanGetMailSettings()
	{
		$host = $this->createApp();
		$provider = $this->getMailProvider();
		$request = new GetApplicationMailRequest();
		$request->setId($host);
		$response = MockHostProvider::execute($request);
		$this->assertTrue($response instanceof GetApplicationMailResponse);

	}

	protected function createApp() : \MongoId
	{
		$app = new Application();
		$host = RandomString::generate(20);
		$app->setDomain('localhost');

		$this->appRepository->insert($app);
		$this->appRepository->setRedisDomain($app->id(), 'localhost');
		return $app->id();
	}

	protected function getUser()
	{
		$user = new UserEntity();
		$this->userRepository->insert($user);
		return $user;
	}

	protected function getMailProvider()
	{
		return MockHostProvider::instance()->container()->get('Pi\ServiceInterface\AbstractMailProvider');
	}
}