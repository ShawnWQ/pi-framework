<?hh

use Mocks\SpotEventMockHost;
use Mocks\AuthMock;
use Mocks\MockHostProvider;
use SpotEvents\ServiceModel\PostNutritionRequest;
use SpotEvents\ServiceModel\PostNutritionResponse;
use SpotEvents\ServiceModel\GetNutritionRequest;
use SpotEvents\ServiceModel\GetNutritionResponse;
use SpotEvents\ServiceModel\GetNutritionSerieRequest;
use SpotEvents\ServiceModel\GetNutritionSerieResponse;
use SpotEvents\ServiceModel\RemoveNutritionSerieRequest;
use SpotEvents\ServiceModel\RemoveNutritionSerieResponse;
use SpotEvents\ServiceModel\PostNutritionSerieRequest;
use SpotEvents\ServiceModel\PostNutritionSerieResponse;
use SpotEvents\ServiceModel\FindNutritionSerieRequest;
use SpotEvents\ServiceModel\FindNutritionSerieResponse;
use SpotEvents\ServiceModel\Dto\NutritionDto;
use SpotEvents\ServiceModel\NutritionSerieDto;
use SpotEvents\ServiceModel\Types\NutritionPlan;
use SpotEvents\ServiceModel\Types\NutritionSerie;
use SpotEvents\ServiceInterface\Data\NutritionRepository;
use SpotEvents\ServiceInterface\Data\NutritionSerieRepository;

class NutritionServiceTest extends \PHPUnit_Framework_TestCase {

	protected $host;

	protected NutritionRepository $nutritionRepo;

	protected NutritionSerieRepository $serieRepo;

	public function setUp()
	{
		$this->host = new SpotEventMockHost();
		$this->host->init();
		AuthMock::mock();
		$this->nutritionRepo = $this->host->tryResolve('SpotEvents\ServiceInterface\Data\NutritionRepository');
		$this->serieRepo = $this->host->tryResolve('SpotEvents\ServiceInterface\Data\NutritionSerieRepository');
	}

	public function testCanGetById()
	{
		$entity = $this->createNutrition();
		$req = new GetNutritionRequest();
		$req->setId($entity->id());

		$res = MockHostProvider::execute($req);
		$this->assertEquals($res->getNutrition()->getName(), $entity->getName());
	}
	public function testCanCreateModality()
	{
		$req = new PostNutritionRequest();
		$req->setName('asdasd');

		$res = MockHostProvider::execute($req);

		$this->assertEquals($res->getNutrition()->getName(), $req->getName());
	}

	public function testCanCreateSerie()
	{
		$req = new PostNutritionSerieRequest();
		$req->setName('test serie');
		$req->setDescription('test description');
		$res = MockHostProvider::execute($req);
		$this->assertNotNull($res->getSerie());
		$this->assertEquals($req->getName(), $res->getSerie()->getName());
	}

	public function testCreateArticleAndAddtoSerie()
	{
		$serie = $this->createSerie();

		$req = new PostNutritionRequest();

		$req->setName('asdasd');
		$req->setSerieId($serie->getId());

		$res = MockHostProvider::execute($req);


		$this->assertEquals($res->getNutrition()->getName(), $req->getName());
		$this->assertEquals($res->getNutrition()->getSerie()['name'], $serie->getName());
	}

	public function testCanRemoveSerie()
	{
		$serie = $this->createSerie();
		$req = new RemoveNutritionSerieRequest();
		$req->setId($serie->getId());

		$res = MockHostProvider::execute($req);
		$db = $this->serieRepo->get($serie->getId());
		$this->assertNull($db);
	}

	public function testCanGetSerie()
	{
		$serie = $this->createSerie();
		$req = new GetNutritionSerieRequest();
		$req->setId($serie->getId());

		$res = MockHostProvider::execute($req);
		$db = $this->serieRepo->get($serie->getId());
		$this->assertNotNull($db);
		$this->assertEquals($serie->getName(), $db->getName());
	}

	protected function createSerie($name = 'asdsd')
	{
		$serie = new NutritionSerie();
		$serie->setName($name);
		$this->serieRepo->insert($serie);
		return $serie;
	}

	protected function createNutrition(string $name = 'random') : NutritionPlan
	{
		$entity = new NutritionPlan();
    $entity->setName($name);
		$this->nutritionRepo->insert($entity);

		return $entity;
	}
}
