<?hh

use Pi\ServiceInterface\Data\JobCarrerRepository;
use Pi\ServiceModel\PostCarrearOfferRequest;
use Pi\ServiceModel\PostCarrearOfferResponse;
use Pi\ServiceModel\FindCarrearOfferRequest;
use Pi\ServiceModel\FindCarrearOfferResponse;
use Pi\ServiceModel\GetCarrearOfferRequest;
use Pi\ServiceModel\GetCarrearOfferResponse;
use Pi\ServiceModel\SaveJobCareerRequest;
use Pi\ServiceModel\SaveJobCareerResponse;
use Pi\ServiceModel\JobCarrerDto;	
use Pi\ServiceModel\Types\JobCarrer;
use Pi\Common\ClassUtils;
use Mocks\OdmContainer;
use Mocks\MockHostProvider;

class CarrersServiceTest extends \PHPUnit_Framework_TestCase {

	protected $carrersRepo;

	public function setUp()
	{
		$container = OdmContainer::get();
		$this->carrersRepo = $container->get('Pi\ServiceInterface\Data\JobCarrerRepository');
	}

	public function testCanCreateCarrer()
	{
		$req = new PostCarrearOfferRequest();
		$req->setTitle('test job carrer');
		$req->setExcerpt('test job carrer');
		$req->setDescription('test job carrer');

		$res = MockHostProvider::execute($req);
		$this->assertEquals($req->getTitle(), $res->getCarrer()->getTitle());
	}

	public function testCanUpdateCareer()
	{
		$career = $this->createCarrer();
		$req = new SaveJobCareerRequest();
		$req->id($career->id());
		$req->setTitle('new-tiitle');
		$req->setExcerpt($career->getExcerpt());
		$req->setDescription($career->getDescription());

		$res = MockHostProvider::execute($req);

		$careerDb = $this->carrersRepo->get($career->id());
		$this->assertEquals($careerDb->getDescription(), $career->getDescription());
		$this->assertEquals($careerDb->getTitle(), $req->getTitle());
	}

	public function testCanGetCarrer()
	{
		$dto = $this->createCarrer();
		$req = new GetCarrearOfferRequest();
		$req->setId($dto->id());

		$response = MockHostProvider::execute($req);

		$this->assertEquals($response->getCarrer()->getTitle(), $dto->getTitle());
	}

	public function testCanFindCarrers()
	{
		$req = new FindCarrearOfferRequest();
		$res = MockHostProvider::execute($req);

		$this->assertTrue(count($res->getCarrers()) > 0);
	}

	protected function createCarrer()
	{
		$entity = new JobCarrer();
		$entity->setTitle('test job carrer');
		$entity->setExcerpt('test job carrer');
		$entity->setDescription('test job carrer');
		$this->carrersRepo->insert($entity);
		return $entity;
	}
}