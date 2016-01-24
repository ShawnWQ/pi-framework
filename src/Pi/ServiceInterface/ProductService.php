<?hh

namespace Pi\ServiceInterface;

use Pi\Service;
use Pi\Extensions;
use Pi\ServiceInterface\Data\ProductRepository;
use Pi\Auth\UserRepository;
use Pi\ServiceModel\GetProductsRequest;
use Pi\ServiceModel\GetProductsResponse;
use Pi\ServiceModel\GetProductRequest;
use Pi\ServiceModel\GetProductResponse;
use Pi\ServiceModel\PostProductRequest;
use Pi\ServiceModel\PostProductResponse;
use Pi\ServiceModel\PostOfferRequest;
use Pi\ServiceModel\PostOfferResponse;
use Pi\ServiceModel\ProductDto;
use Pi\ServiceModel\Types\Offer;
use Pi\ServiceModel\Types\Product;
use Pi\ServiceModel\Types\FeedAction;
use Pi\Common\ClassUtils;
use Pi\HttpResult;

class ProductService extends Service {

	public ProductRepository $productRepo;

	public UserFeedBusiness $userFeedBus;
	
	public UserRepository $userRepo;

	public OfferCreateBusiness $offerCreateBus;

	const productNotFoundError = 'Product doesnt exists';

	<<Request,Route('/product'),Method('POST')>>
	public function postProduct(PostProductRequest $req)
	{
		$entity = new Product();
		$entity->setOffers(array());
		ClassUtils::mapDto($req, $entity);

		$this->productRepo->insert($entity);

		$url = Extensions::validateInputUrl($req->getUrl())
		? $req->getUrl()
		: Extensions::getUrl($this->appConfig(), 'product', $entity->getId(), $req->getName());

		$this->productRepo->queryBuilder()
			->update()
			->field('id')->eq($entity->getId())
			->field('url')->set($url)
			->getQuery()
			->execute();

		$dto = new ProductDto();
		ClassUtils::mapDto($entity, $dto);
		$dto->setUrl($url);
		$response = new PostProductResponse();
		$response->setProduct($dto);
		return $response;
	}

	<<Request,Route('/product-offer/:productId'),Method('POST')>>
	public function postProductOffer(PostOfferRequest $request)
	{
		if(!$this->productRepo->exists($request->getProductId())) {
			return HttpResult::notFound(self::productNotFoundError, _(self::productNotFoundError));
		}

		$entity = new Offer();
		ClassUtils::mapDto($request, $entity);
		$entity->setAvailableAtFor($request->getProductId());

		$this->offerCreateBus->create($request->getProductId(), $entity);

		$response = new PostOfferResponse();
		return $response;
	}

	<<Request,Route('/product/:id'),Method('GET')>>
	public function getProduct(GetProductRequest $request)
	{
		$response = new GetProductResponse();
		$dto = $this->productRepo->getAs($request->getId(), 'Pi\ServiceModel\ProductDto');

		if(is_null($dto)) {
			return HttpResult::notFound(self::productNotFoundError, _(self::productNotFoundError));
		}

		$response->setProduct($dto);
		return $response;
	}

  	<<Request,Route('/product'),Method('GET')>>
	public function getProducts(GetProductsRequest $request)
	{
		$response = new GetProductsResponse();
		$query = $this->productRepo->queryBuilder('Pi\ServiceModel\ProductDto')
	      ->find();

	    $this->prepareFindQuery($query, $request);

	    $data = $query
	      ->hydrate()
	      ->getQuery()
	      ->execute();

		$response->setProducts($data);
		return $response;
	}

	protected function prepareFindQuery(&$query, GetProductsRequest $request)
	{

	}
}
