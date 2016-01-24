<?hh

use Mocks\SpotEventMockHost;
use Mocks\AuthMock;
use Mocks\MockHostProvider;
use SpotEvents\ServiceModel\PostWorkoutRequest;
use SpotEvents\ServiceModel\PostWorkoutResponse;
use SpotEvents\ServiceModel\GetWorkoutRequest;
use SpotEvents\ServiceModel\GetWorkoutResponse;
use SpotEvents\ServiceModel\GetWorkoutSerieRequest;
use SpotEvents\ServiceModel\GetWorkoutSerieResponse;
use SpotEvents\ServiceModel\RemoveWorkoutSerieRequest;
use SpotEvents\ServiceModel\RemoveWorkoutSerieResponse;
use SpotEvents\ServiceModel\PostWorkoutSerieRequest;
use SpotEvents\ServiceModel\PostWorkoutSerieResponse;
use SpotEvents\ServiceModel\FindWorkoutSerieRequest;
use SpotEvents\ServiceModel\FindWorkoutSerieResponse;
use SpotEvents\ServiceModel\Dto\WorkoutDto;
use SpotEvents\ServiceModel\WorkoutSerieDto;
use SpotEvents\ServiceModel\Types\Workout;
use SpotEvents\ServiceModel\Types\WorkoutSerie;
use SpotEvents\ServiceInterface\Data\WorkoutRepository;
use SpotEvents\ServiceInterface\Data\WorkoutSerieRepository;

class WorkoutServiceTest extends \PHPUnit_Framework_TestCase {

	protected $host;

	protected WorkoutRepository $workoutRepo;

	protected WorkoutSerieRepository $serieRepo;

	public function setUp()
	{
		$this->host = new SpotEventMockHost();
		$this->host->init();
		AuthMock::mock();
		$this->workoutRepo = $this->host->tryResolve('SpotEvents\ServiceInterface\Data\WorkoutRepository');
		$this->serieRepo = $this->host->tryResolve('SpotEvents\ServiceInterface\Data\WorkoutSerieRepository');
	}

	public function testCanGetById()
	{
		$entity = $this->createWorkout();
		$req = new GetWorkoutRequest();
		$req->setId($entity->id());

		$res = MockHostProvider::execute($req);
		$this->assertEquals($res->getWorkout()->getName(), $entity->getName());
	}
	public function testCanCreateModality()
	{
		$req = new PostWorkoutRequest();
		$req->setName('asdasd');

		$res = MockHostProvider::execute($req);

		$this->assertEquals($res->getWorkout()->getName(), $req->getName());
	}

	public function testCanCreateSerie()
	{
		$req = new PostWorkoutSerieRequest();
		$req->setName('test serie');
		$req->setDescription('test description');
		$res = MockHostProvider::execute($req);
		$this->assertNotNull($res->getSerie());
		$this->assertEquals($req->getName(), $res->getSerie()->getName());
	}

	public function testCreateArticleAndAddtoSerie()
	{
		$serie = $this->createSerie();

		$req = new PostWorkoutRequest();

		$req->setName('asdasd');
		$req->setSerieId($serie->getId());

		$res = MockHostProvider::execute($req);


		$this->assertEquals($res->getWorkout()->getName(), $req->getName());
		$this->assertEquals($res->getWorkout()->getSerie()['name'], $serie->getName());
	}

	public function testCanRemoveSerie()
	{
		$serie = $this->createSerie();
		$req = new RemoveWorkoutSerieRequest();
		$req->setId($serie->getId());

		$res = MockHostProvider::execute($req);
		$db = $this->serieRepo->get($serie->getId());
		$this->assertNull($db);
	}

	public function testCanGetSerie()
	{
		$serie = $this->createSerie();
		$req = new GetWorkoutSerieRequest();
		$req->setId($serie->getId());

		$res = MockHostProvider::execute($req);
		$db = $this->serieRepo->get($serie->getId());
		$this->assertNotNull($db);
		$this->assertEquals($serie->getName(), $db->getName());
	}

	protected function createSerie($name = 'asdsd')
	{
		$serie = new WorkoutSerie();
		$serie->setName($name);
		$this->serieRepo->insert($serie);
		return $serie;
	}

	protected function createWorkout(string $name = 'random') : Workout
	{
		$entity = new Workout();
    $entity->setName($name);
		$this->workoutRepo->insert($entity);

		return $entity;
	}
}
