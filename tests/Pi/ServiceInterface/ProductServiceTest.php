<?hh

use Mocks\OdmContainer;
use Mocks\MockHostProvider;
use Mocks\AuthMock;
use Pi\HttpResult;
use Pi\Extensions;
use Pi\Auth\UserRepository;
use Pi\ServiceInterface\ProductService;
use Pi\ServiceInterface\Data\UserFriendRequestRepository;
use Pi\ServiceInterface\Data\UserFriendRepository;
use Pi\ServiceInterface\Data\ProductRepository;
use Pi\ServiceInterface\UserFriendBusiness;
use Pi\ServiceModel\GetProductRequest;
use Pi\ServiceModel\GetProductResponse;
use Pi\ServiceModel\GetProductsRequest;
use Pi\ServiceModel\GetProductsResponse;
use Pi\ServiceModel\PostProductRequest;
use Pi\ServiceModel\PostProductResponse;
use Pi\ServiceModel\PostOfferRequest;
use Pi\ServiceModel\PostOfferResponse;
use Pi\ServiceModel\ProductDto;
use Pi\ServiceModel\Types\Product;
use Pi\ServiceModel\Types\PriceSpecification;


class ProductServiceTest extends \PHPUnit_Framework_TestCase {

	protected ProductRepository $productRepo;

	public function setUp()
	{
		$container = OdmContainer::get();
		$this->productRepo = $container->get('Pi\ServiceInterface\Data\ProductRepository');
		if(is_null($this->productRepo)) {
			throw new \Exception('ProductRepository not registered in IOC');
		}
	}

	public function testCantCreateOfferForNonExistingProduct()
	{
		AuthMock::mock();
		$req = new PostOfferRequest();
		$req->setProductId(new \MongoId());
		$res = MockHostProvider::execute($req);
		$this->assertTrue($res instanceof HttpResult);
		$this->assertEquals($res->response()['errorCode'], ProductService::productNotFoundError);
	}

	public function testCreateOffer()
	{
		AuthMock::mock();
		$req = new PostOfferRequest();
		$product = $this->createProduct();
		$req->setProductId($product->id());
		

		$res = MockHostProvider::execute($req);
	}

	public function testCreateProduct()
	{
		AuthMock::mock();
		$req = new PostProductRequest();
		$req->setName('asdasdasd');
		$res = MockHostProvider::execute($req);

		$this->assertEquals($res->getProduct()->getName(), $req->getName());
	}

	public function testCreateProductAndGenerateUrl()
	{
		AuthMock::mock();
		$req = new PostProductRequest();
		$req->setName('sadasdasd');
		$res = MockHostProvider::execute($req);
		
		$this->assertTrue(Extensions::validateInputUrl($res->getProduct()->getUrl()));
		
		$products = $this->productRepo->queryBuilder()
			->find()
			->field('url')->eq($res->getProduct()->getUrl())
			->getQuery()
			->execute();

		$this->assertEquals(count($products), 1);
	}

	public function testGetProduct()
	{
		AuthMock::mock();
		$product = $this->createProduct();
		$req = new GetProductRequest();
		$req->setId($product->id());
		$res = MockHostProvider::execute($req);

		$this->assertEquals($product->getName(), $res->getProduct()->getName());
	}

	public function testGetProducts()
	{
		AuthMock::mock();
		$this->createProduct();
		$req = new GetProductsRequest();

		$res = MockHostProvider::execute($req);

		$this->assertTrue(count($res->getProducts()) > 0);
	}

	protected function createProduct($name = 'test user') : Product
	{
		$entity = new Product();
		$entity->setName($name);
		$this->productRepo->insert($entity);
		return $entity;
	}
}
