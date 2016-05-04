<?hh

use Mocks\SpotEventMockHost;
use Mocks\AuthMock;
use Mocks\MockHostProvider;
use SpotEvents\ServiceModel\PostModalityRequest;
use SpotEvents\ServiceModel\PostModalityResponse;
use SpotEvents\ServiceModel\GetModalityRequest;
use SpotEvents\ServiceModel\GetModalityResponse;
use SpotEvents\ServiceModel\GetModalitiesRequest;
use SpotEvents\ServiceModel\GetModalitiesResponse;
use SpotEvents\ServiceModel\Dto\ModalityDto;
use SpotEvents\ServiceModel\Types\Modality;
use SpotEvents\ServiceInterface\Data\ModalityRepository;

class ModalityServiceTest extends \PHPUnit_Framework_TestCase {

	protected $host;

	protected ModalityRepository $modalityRepo;

	public function setUp()
	{
		$this->host = new SpotEventMockHost();
		$this->host->init();
		$this->modalityRepo = $this->host->tryResolve('SpotEvents\ServiceInterface\Data\ModalityRepository');
	}

	public function testCanGetById()
	{
		$modality = $this->createModality();
		$req = new GetModalityRequest();
		$req->setId($modality->id());

		$res = MockHostProvider::execute($req);
		$this->assertEquals($res->getModality()->getTitle(), $modality->getTitle());
	}

	public function testCanFindModalities()
	{
		$this->createModality();
		$req = new GetModalitiesRequest();

		$res = MockHostProvider::execute($req);
		$this->assertTrue(count($res->getModalities()) > 0);
	}

	public function testCanCreateModality()
	{
		$req = new PostModalityRequest();
		$req->setTitle('test');
		$req->setDescription('dasasd');

		$res = MockHostProvider::execute($req);

		$this->assertEquals($res->getModality()->getTitle(), $req->getTitle());
	}

	protected function createModality(string $name = 'random') : Modality
	{
		$entity = new Modality();
		$entity->setTitle($name);
		$entity->setDescription('asdasd');

		$this->modalityRepo->insert($entity);

		return $entity;
	}
}
