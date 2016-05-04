<?hh

use Mocks\OdmContainer,
	Mocks\MockHostProvider,
	Mocks\AuthMock,
	Pi\ServiceInterface\Data\PlaceRepository,
	Pi\ServiceModel\GetPlaceRequest,
	Pi\ServiceModel\GetPlaceResponse,
	Pi\ServiceModel\FindPlaceRequest,
	Pi\ServiceModel\FindPlaceResponse,
	Pi\ServiceModel\PostPlaceRequest,
	Pi\ServiceModel\PostPlaceResponse,
	Pi\ServiceModel\PostPlaceOpeningHoursRequest,
	Pi\ServiceModel\PlaceDto,
	Pi\ServiceModel\Types\Place,
	Pi\ServiceModel\Types\PlaceType,
	Pi\ServiceModel\Types\GeoCoordinates;


class PlaceServiceTest extends \PHPUnit_Framework_TestCase {

	protected $placeRepo;

	public function setUp()
	{
		$container = OdmContainer::get();
		$this->placeRepo = $container->get('Pi\ServiceInterface\Data\PlaceRepository');
	}

	public function testCanFindPlace()
	{
		$this->createPlace('asdasd asd');
		$req = new FindPlaceRequest();
		$res = MockHostProvider::execute($req);

		$this->assertTrue(count($res->getPlaces()) > 0);
	}

	public function testCanGetPlace()
	{
		$place = $this->createPlace('asdasd asd');
		$req = new GetPlaceRequest();
    	$req->setId($place->id());

		$res = MockHostProvider::execute($req);

		$this->assertEquals($res->getPlace()->getName(), $place->getName());
	}

	public function testCanCreatePlace()
	{
		$req = $this->getCreateRequest();

		$res = MockHostProvider::execute($req);

		$this->assertEquals($res->getPlace()->getAddress(), $req->getAddress());
	}

	public function testCanCreatePlaceWithGeo()
	{
		$req = $this->getCreateRequest();
		$res = MockHostProvider::execute($req);
		$entity = $this->placeRepo->get($res->getPlace()->getId());
		$this->assertTrue($entity->getGeo() instanceof GeoCoordinates);
		$this->assertEquals($entity->getGeo()->getLatitude(), $req->getLatitude());
	}

	public function testCanAddOpeningHours()
	{
		$place = $this->createPlace();
		$req = new PostPlaceOpeningHoursRequest();
		$res = MockHostProvider::execute($req);
		$entity = $this->placeRepo->get($place->getId());
		$this->assertTrue(count($entity->getOpeningHours()) === 1);
	}

	protected function getCreateRequest($name = 'asdasd')
	{
		$req = new PostPlaceRequest();
	    $req->setAddress('asdasdsad');
	    $req->setLatitude(40.642320);
	    $req->setLongitude(-7.950832);
	    $req->setElevation(123123123);
	    $req->setName($name);

	    return $req;
	}

	protected function createPlace($name = 'test user')
	{
		$entity = new Place();
		$entity->setAddress('asdasdsad');
	    $geo = new GeoCoordinates();
	    $geo->setLatitude(40.642320);
	    $geo->setLongitude(-7.950832);
	    $geo->setElevation(123123123);
	    $entity->setGeo($geo->jsonSerialize());
	    $entity->setName($name);
	    $entity->setOpeningHours(array(array()));
	    $entity->setTypes(array(PlaceType::CivicStructureHospital));

		$this->placeRepo->insert($entity);
		return $entity;
	}

}
