<?hh

use Mocks\OdmContainer,
	Mocks\MockHostProvider,
	Mocks\AuthMock,
	Pi\ServiceInterface\ArticleService,
	Pi\ServiceModel\RemoveWorkReffer,
	Pi\ServiceModel\RemoveWorkRefferResponse,
	Pi\ServiceModel\PostArticleKeywordsRequest,
	Pi\ServiceModel\PostArticleKeywordsResponse,
	Pi\ServiceInterface\Data\ArticleRepository,
	Pi\ServiceInterface\Data\ArticleSerieRepository,
	Pi\ServiceInterface\Data\ArticleCategoryRepository,
	Pi\ServiceModel\PostWorkPublishDate,
	Pi\ServiceModel\PostWorkState,
	Pi\ServiceModel\ArticleNormalizeAllRequest,
	Pi\ServiceModel\ArticleNormalizeAllResponse,
	Pi\ServiceModel\RemoveArticleRequest,
	Pi\ServiceModel\RemoveArticleResponse,
	Pi\ServiceModel\RemoveArticleCategoryRequest,
	Pi\ServiceModel\PostWorkCategory,
	Pi\ServiceModel\PostWorkCategoryResponse,
	Pi\ServiceModel\RemoveArticleCategoryResponse,
	Pi\ServiceModel\PostArticleSerieRequest,
	Pi\ServiceModel\PostArticleSerieResponse,
	Pi\ServiceModel\RemoveArticleSerieRequest,
	Pi\ServiceModel\RemoveArticleSerieResponse,
	Pi\ServiceModel\FindArticleSerieRequest,
	Pi\ServiceModel\FindArticleSerieResponse,
	Pi\ServiceModel\PostWorkReffer,
	Pi\ServiceModel\PostWorkRefferResponse,
	Pi\ServiceModel\ArticleState,
	Pi\ServiceModel\GetArticleSerieRequest,
	Pi\ServiceModel\GetArticleSerieResponse,
	Pi\ServiceModel\GetArticleRequest,
	Pi\ServiceModel\GetArticleResponse,
	Pi\ServiceModel\PostArticleRequest,
	Pi\ServiceModel\PostArticleResponse,
	Pi\ServiceModel\FindArticleRequest,
	Pi\ServiceModel\FindArticleResponse,
	Pi\ServiceModel\ArticleDto,
	Pi\ServiceModel\ArticleSerieDto,
	Pi\ServiceModel\ArticleCategoryDto,
	Pi\ServiceModel\Types\ArticleCategoryEmbed,
	Pi\ServiceModel\Types\Article,
	Pi\ServiceModel\Types\ArticleSerie,
	Pi\ServiceModel\Types\ArticleCategory,
	Pi\ServiceModel\Types\GeoCoordinates,
	Pi\ServiceModel\GetArticleCategoryRequest,
	Pi\ServiceModel\GetArticleCategoryResponse,
	Pi\ServiceModel\FindArticleCategoryRequest,
	Pi\ServiceModel\FindArticleCategoryResponse,
	Pi\ServiceModel\PostArticleCategoryRequest,
	Pi\ServiceModel\PostArticleCategoryResponse,
	Pi\ServiceModel\PutArticleRequest,
	Pi\ServiceModel\PutArticleResponse,
	Pi\Common\RandomString,
	Pi\PhpUnitUtils;




class ArticleServiceTest extends \PHPUnit_Framework_TestCase {

	protected $ArticleRepo;

	protected ArticleCategoryRepository $categoryRepo;

	protected ArticleSerieRepository $serieRepo;

	public function setUp()
	{
		$container = OdmContainer::get();
		$this->ArticleRepo = $container->get('Pi\ServiceInterface\Data\ArticleRepository');
		$this->categoryRepo = $container->get('Pi\ServiceInterface\Data\ArticleCategoryRepository');
		$this->serieRepo = $container->get('Pi\ServiceInterface\Data\ArticleSerieRepository');
	}



	public function testCanCreateArticle()
	{
		$req = $this->createArticleRequest();
		$category = $this->createCategory();
		$req->setCategoryId($category->getId());

		$res = MockHostProvider::execute($req);
		die(print_r($res));
		$this->assertEquals($res->getArticle()->getName(), $req->getName());

		$dto = $this->ArticleRepo->get($res->getArticle()->getId());
		
		$this->assertTrue($dto->getCategory() instanceof ArticleCategoryEmbed);
		$this->assertEquals($dto->getCategory()->getDisplayName(), $category->getDisplayName());
		$this->assertTrue($dto->getCreatedDate() > new \DateTime('now'));
		
	}

	public function testCanNormalizeAllArticles()
	{

		$entity = new Article();
	    $entity->setName('test-normalize-all');
	    $entity->setArticleBody('test-normalize-all');
		$entity->setHeadline('test-normalize-all');

		$this->ArticleRepo->insert($entity);

		$req = new ArticleNormalizeAllRequest();
		$res = MockHostProvider::execute($req);

		$article = $this->ArticleRepo->get($entity->getId());
		$this->assertEquals($article->getUrlName(), $entity->getName());
	}

	public function testCanGenerateUrl()
	{
		$service = $this->getArticleService();
		$method = PhpUnitUtils::getMethod($service, 'getUrl');
		$id = new \MongoId();
      	$uri = $method->invoke($service, $id, 'title-test');
      	$this->assertEquals($uri, 'http//localhost/title-test/art-' . (string)$id);

      	$uri = $method->invoke($service, $id, 'title-test', 'root-child');
      	$this->assertEquals($uri, 'http//localhost/title-test_root-child/art-' . (string)$id);

	}

	public function testCanChangeReffer()
	{
		$service = $this->getArticleService();
		$article = $this->createArticle();
		$req = new PostWorkReffer();
		$req->setId($article->getId());
		$req->setRefferName(RandomString::generate());
		$req->setRefferImage(RandomString::generate());
		$req->setRefferUrl(RandomString::generate());

		$res = MockHostProvider::execute($req);
		$articleDb = $this->ArticleRepo->get($article->getId());
		$this->assertEquals($articleDb->getRefferName(), $req->getRefferName());
		$this->assertEquals($articleDb->getRefferImage(), $req->getRefferImage());
		$this->assertEquals($articleDb->getRefferUrl(), $req->getRefferUrl());
	}

	public function testCanRemoveReffer()
	{
		$service = $this->getArticleService();
		$article = new Article();
		$article->setRefferName(RandomString::generate());
		$article->setRefferImage(RandomString::generate());
		$article->setRefferUrl(RandomString::generate());
		$this->ArticleRepo->insert($article);


		$req = new RemoveWorkReffer();
		$req->setId($article->getId());
		
		$res = MockHostProvider::execute($req);
		$articleDb = $this->ArticleRepo->get($article->getId());
		$this->assertEquals($articleDb->getRefferName(), null);
		$this->assertEquals($articleDb->getRefferImage(), null);
		$this->assertEquals($articleDb->getRefferUrl(), null);
	}

	public function testCanChangePublishedDate()
	{
		$service = $this->getArticleService();
		$article = $this->createArticle('asdasd asd');
		$req = new PostWorkPublishDate();
		$req->setId($article->getId());
		$req->setDate(new \DateTime('now'));
    	
		$res = MockHostProvider::execute($req);

		$article = $this->ArticleRepo->get($article->getId());
		$this->assertEquals(new \DateTime($article->getDatePublished()), $req->getDate());
	}

	public function testChangeState()
	{
		$service = $this->getArticleService();
		$article = $this->createArticle('asdasd asd');
		$req = new PostWorkState();
		$req->setId($article->getId());
		$req->setState(ArticleState::Removed);
    	
		$res = MockHostProvider::execute($req);

		$articleDb = $this->ArticleRepo->get($article->getId());
		$this->assertEquals($articleDb->getState(), ArticleState::Removed);
		$this->assertEquals($articleDb->getName(), $article->getName());
		$this->assertEquals($articleDb->getHeadline(), $article->getHeadline());
	}

	public function testChangeCategory()
	{
		$service = $this->getArticleService();
		$cat = $this->createCategory();
		$article = $this->createArticle('asdasd asd', $cat->getId());
		$newCat = $this->createCategory();
		$req = new PostWorkCategory();
		$req->setId($article->getId());
		$req->setCategoryId($newCat->getId());
    	
		$res = MockHostProvider::execute($req);

		$articleDb = $this->ArticleRepo->get($article->getId());

		$this->assertEquals($articleDb->getCategoryPath(), ArticleService::formatPath($newCat->getId()));
	}

	public function testServiceRegisterCustomType()
	{
		$service = $this->getArticleService();
		if(is_null($service)) {
			throw new \Exception('ArticleService isnt registered');
		}

		$this->assertEquals($service->getCustomType(), 'Pi\ServiceModel\Types\Article');
		$this->assertEquals($service->getCustomTypeShort(), 'art');
	}

	public function testGetId()
	{
		$name = 'Teste De Nome';
		$id = ArticleService::getArticleId($name);
		$this->assertEquals($id, 'teste-de-nome');
	}

	public function testCanGetArticle()
	{
		$Article = $this->createArticle('asdasd asd');
		$req = new GetArticleRequest();
    	$req->setId($Article->id());

		$res = MockHostProvider::execute($req);

		$this->assertEquals($res->getArticle()->getName(), $Article->getName());
	}

	public function setQueryUrl()
	{
		$service = $this->getArticleService();
		$article = $this->createArticle();
		$service::querySetUrl($this->ArticleRepo->queryBuilder(), $article->getId(), 'new-url', 'url-name');
		$articleDb = $this->ArticleRepo->get($article->getId());
		$this->assertEquals($articleDb->getUrlName(), 'url-name');
	}

  	public function testCanFindArticle()
	{
		$this->createArticle('asdasd asd');
		$req = new FindArticleRequest();
		$res = MockHostProvider::execute($req);

		$this->assertTrue(count($res->getArticles()) > 0);
	}

	public function testCanFindArticleByCategoryDescendants()
	{
		$cat = $this->createCategory();

		$req = new FindArticleRequest();
		$req->setCategoryId($cat->getId());
		$res = MockHostProvider::execute($req);

		$this->assertTrue(count($res->getArticles()) === 0);

		$this->createArticle('asdasd asd', $cat->getId());
		$req = new FindArticleRequest();
		$req->setCategoryId($cat->getId());
		$res = MockHostProvider::execute($req);

		$this->assertTrue(count($res->getArticles()) === 1);
	}

	public function testCanRemoveArticleById()
	{
		$art = $this->createArticle();

		$req = new RemoveArticleRequest();
		$req->setId($art->getId());
		MockHostProvider::execute($req);

		$artDb = $this->ArticleRepo->get($art->getId());
		$this->assertTrue(is_null($artDb));
	}

	public function testCanRemoveArticleCategoryById()
	{
		$cat = $this->createCategory();

		$req = new RemoveArticleCategoryRequest();
		$req->setId($cat->getId());
		MockHostProvider::execute($req);

		$catDb = $this->categoryRepo->get($cat->getId());
		$this->assertTrue(is_null($catDb));
	}

	public function testCanFindArticleByName()
	{
		$rand = RandomString::generate(15);
		$art = $this->createArticle('the first words ' . $rand);
		$req = new FindArticleRequest();
		$req->setName(RandomString::generate(50));

		$res = MockHostProvider::execute($req);
		$this->assertTrue(count($res->getArticles()) === 0);

		$req->setName('the first words');
		$res = MockHostProvider::execute($req);
		$this->assertTrue(count($res->getArticles()) > 0);

		$req->setName('the first words ' . $rand);
		$res = MockHostProvider::execute($req);
		$this->assertTrue(count($res->getArticles()) === 1);
	}

	public function testCanUpdateArticle()
	{

		$article = $this->createArticle();
		$req = new PutArticleRequest();
		$req->setId($article->getId());
		$req->setName($article->getName());
		$req->setHeadline('asdasd');

		$res = MockHostProvider::execute($req);

		$articleDb = $this->ArticleRepo->get($article->getId());
		$this->assertEquals($articleDb->getHeadline(), $req->getHeadline());
	}

	public function testCreateArticleAndCategoryPath()
	{
		$req = $this->createArticleRequest();
		$cat = $this->createCategory();
		$req->setCategoryId($cat->getId());

		$res = MockHostProvider::execute($req);
		$this->assertEquals($res->getArticle()->getCategoryPath(), ',' . $cat->getId() . ',');
	}

	public function testCreateArticleWithReffer()
	{
		$req = $this->createArticleRequest();
		$req->setRefferName('Jornal de Viseu');
		$req->setRefferImage('https://google.com/image.png');
		$req->setRefferUrl('https://google.com');

		$res = MockHostProvider::execute($req);
		$this->assertEquals($res->getArticle()->getRefferName(), $req->getRefferName());
		$this->assertEquals($res->getArticle()->getRefferImage(), $req->getRefferImage());
		$this->assertEquals($res->getArticle()->getRefferUrl(), $req->getRefferUrl());

		$article = $this->ArticleRepo->get($res->getArticle()->getId());
		$this->assertEquals($article->getRefferName(), $req->getRefferName());
		$this->assertEquals($article->getRefferImage(), $req->getRefferImage());
		$this->assertEquals($article->getRefferUrl(), $req->getRefferUrl());
	}

	public function testCanCreateCategory()
	{
		$req = new PostArticleCategoryRequest();
		$req->setDisplayName(RandomString::generate());
		$parent = $this->createCategory();
		$req->setParent($parent->getId());
		
		$res = MockHostProvider::execute($req);

		$this->assertEquals($res->getCategory()->getDisplayName(), $req->getDisplayName());
	}

	public function testCanGetCategory()
	{
		$req = new GetArticleCategoryRequest();
		$cat = $this->createCategory();
		$req->setId($cat->id());

		$res = MockHostProvider::execute($req);
		
		$this->assertEquals($res->getCategory()->getDisplayName(), $cat->getDisplayName());
	}

	public function testCanFindCategory()
	{
		$req = new FindArticleCategoryRequest();
		$cat = $this->createCategory();

		$res = MockHostProvider::execute($req);
		
		$this->assertTrue(count($res->getCategories()) > 0);
	}

	public function testCanCreateSerie()
	{
		$req = new PostArticleSerieRequest();
		$req->setName('test serie');
		$req->setDescription('test description');

		$res = MockHostProvider::execute($req);
		
		$this->assertNotNull($res->getSerie());
		$this->assertEquals($req->getName(), $res->getSerie()->getName());
	}

	public function testCreateArticleAndAddtoSerie()
	{
		$serie = $this->createSerie();
		$req = $this->createArticleRequest();
		$req->setSerieId($serie->getId());

		$res = MockHostProvider::execute($req);

		$this->assertEquals($res->getArticle()->getName(), $req->getName());
		$this->assertEquals($res->getArticle()->getSerie()->getName(), $serie->getName());
	}

	public function testCanRemoveSerie()
	{
		$serie = $this->createSerie();
		$req = new RemoveArticleSerieRequest();
		$req->setId($serie->getId());

		$res = MockHostProvider::execute($req);
		$db = $this->serieRepo->get($serie->getId());
		$this->assertNull($db);
	}

	public function testCanGetSerie()
	{
		$serie = $this->createSerie();
		$req = new GetArticleSerieRequest();
		$req->setId($serie->getId());

		$res = MockHostProvider::execute($req);
		$db = $this->serieRepo->get($serie->getId());
		$this->assertNotNull($db);
		$this->assertEquals($serie->getName(), $db->getName());
	}

	public function testCanAddKeywordsToArticle()
	{
		$article = $this->createArticle();
		$request = new PostArticleKeywordsRequest(array('first', 'second'), $article->id());
		$response = MockHostProvider::execute($request);
	}

	protected function createSerie($name = 'asdsd')
	{
		$serie = new ArticleSerie();
		$serie->setName($name);
		$this->serieRepo->insert($serie);
		return $serie;
	}

	protected function createArticleRequest($name = 'asdasd') : PostArticleRequest
	{
		$req = new PostArticleRequest();
		$req->setName($name);
	    $req->setArticleBody('asdasdasd');
		$req->setHeadline('headline');
		return $req;
	}

	protected function createArticle($name = 'test user', $catId = null)
	{
		$entity = new Article();
		if(!is_null($catId)) {
			$entity->setCategoryPath(',' . $catId . ',');
		}
	    $entity->setName($name);
	    $entity->setArticleBody('asdasdasd');
		$entity->setHeadline('headline');

		$this->ArticleRepo->insert($entity);
		return $entity;
	}

	protected function createCategory($name = 'test user')
	{
		$entity = new ArticleCategory();
		$entity->setId(RandomString::generate());
  		$entity->setDisplayName(RandomString::generate());

		$this->categoryRepo->insert($entity);
		return $entity;
	}

	protected function getArticleService()
	{
		return OdmContainer::get()->get('Pi\ServiceInterface\ArticleService');
	}
}