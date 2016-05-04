<?hh

use Mocks\OdmContainer;
use Mocks\MockHostProvider;
use Mocks\AuthMock;
use Pi\ServiceInterface\Data\UserFriendRequestRepository;
use Pi\ServiceInterface\Data\UserFriendRepository;
use Pi\Auth\UserRepository;
use Pi\ServiceInterface\UserFriendBusiness;
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
use Pi\ServiceModel\Types\AlbumImage;

class AlbumServiceTest extends \PHPUnit_Framework_TestCase {

	protected $albumRepo;
	protected $imageRepo;

	public function setUp()
	{
		$container = OdmContainer::get();
		$this->albumRepo = $container->get('Pi\ServiceInterface\Data\AlbumRepository');
		$this->imageRepo = $container->get('Pi\ServiceInterface\Data\AlbumImageRepository');
	}

	public function tearDown()
	{
		OdmContainer::dispose();
	}

	public function testCanGetAlbuns()
	{
		$this->createAlbum();
		$req = new GetAlbunsRequest();

		$res = MockHostProvider::execute($req);

		$this->assertTrue(count($res->getAlbuns()) > 0);
	}

	public function testCanGetImages()
	{
		$img = $this->createImage();
		$req = new GetAlbumRequest();
		$req->setAlbumId($img->getAlbumId());

		$res = MockHostProvider::execute($req);

		$this->assertTrue(count($res->getImages()) > 0);
	}

	public function testCreateAlbum()
	{
		AuthMock::mock();
		$req = new PostAlbumRequest();
		$req->setTitle('asd');
		$res = MockHostProvider::execute($req);

		$this->assertEquals($res->getAlbum()->getTitle(), $req->getTitle());

	}

	public function testCreateImage()
	{
		AuthMock::mock();
		$req = new PostAlbumImage();
		$albumId = $this->createAlbum()->id();
		$req->setText('asdasd');
		$req->setImageSrc('asdasd');
		$req->setAlbumId($albumId);
		$res = MockHostProvider::execute($req);
		$img = $this->imageRepo->get($res->getImage()->id());
		$this->assertEquals($img->getText(), $req->getText());

	}

	protected function createAlbum($name = 'test user')
	{
		$entity = new Album();
		$entity->setTitle($name);
		$this->albumRepo->insert($entity);
		return $entity;
	}

	protected function createImage($id = null, $name = 'test user')
	{
		if(is_null($id)) {
			$id = $this->createAlbum()->id();
		}
		$entity = new AlbumImage();
		$entity->setText($name);
		$entity->setImageSrc($name);
		$entity->setAlbumId($id);
		$this->imageRepo->insert($entity);
		return $entity;
	}
}
