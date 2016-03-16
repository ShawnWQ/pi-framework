<?hh

namespace Pi\ServiceInterface;

use Pi\Service,
	Pi\HttpResult,
	Pi\Host\HostProvider,
	Pi\ServiceModel\RemoveWorkReffer,
	Pi\ServiceModel\RemoveWorkRefferResponse,
	Pi\ServiceModel\PostWorkReffer,
	Pi\ServiceModel\PostWorkRefferResponse,
	Pi\ServiceModel\PostWorkPublishDate,
	Pi\ServiceModel\PostWorkPublishDateResponse,
	Pi\ServiceModel\PostWorkState,
	Pi\ServiceModel\PostWorkStateResponse,
	Pi\ServiceModel\PostArticleSerieRequest,
	Pi\ServiceModel\PostArticleSerieResponse,
	Pi\ServiceModel\RemoveArticleRequest,
	Pi\ServiceModel\RemoveArticleResponse,
	Pi\ServiceModel\RemoveArticleCategoryRequest,
	Pi\ServiceModel\RemoveArticleCategoryResponse,
	Pi\ServiceModel\ArticleNormalizeAllRequest,
	Pi\ServiceModel\ArticleNormalizeAllResponse,
	Pi\ServiceModel\GetArticleRequest,
	Pi\ServiceModel\GetArticleResponse,
	Pi\ServiceModel\GetArticleSerieRequest,
	Pi\ServiceModel\GetArticleSerieResponse,
	Pi\ServiceModel\RemoveArticleSerieRequest,
	Pi\ServiceModel\RemoveArticleSerieResponse,
	Pi\ServiceModel\PostArticleRequest,
	Pi\ServiceModel\PostArticleResponse,
	Pi\ServiceModel\PutArticleRequest,
	Pi\ServiceModel\PutArticleResponse,
	Pi\ServiceModel\FindArticleRequest,
	Pi\ServiceModel\FindArticleResponse,
	Pi\ServiceModel\FindArticleSerieRequest,
	Pi\ServiceModel\FindArticleSerieResponse,
	Pi\ServiceModel\FindArticleCategoryRequest,
	Pi\ServiceModel\FindArticleCategoryResponse,
	Pi\ServiceModel\GetArticleCategoryRequest,
	Pi\ServiceModel\GetArticleCategoryResponse,
	Pi\ServiceModel\PostArticleCategoryRequest,
	Pi\ServiceModel\PostArticleCategoryResponse,
	Pi\ServiceModel\PostArticleKeywordsRequest,
	Pi\ServiceModel\PostArticleKeywordsResponse,
	Pi\ServiceModel\PostWorkCategory,
	Pi\ServiceModel\PostWorkCategoryResponse,
	Pi\ServiceModel\ArticleDto,
	Pi\ServiceModel\ArticleSerieDto,
	Pi\ServiceModel\ArticleCategoryDto,
	Pi\ServiceModel\Types\ArticleCategoryEmbed,
	Pi\ServiceModel\Types\Article,
	Pi\ServiceModel\Types\ArticleSerie,
	Pi\ServiceModel\Types\ArticleSerieEmbed,
	Pi\ServiceModel\Types\ArticleCategory,
	Pi\ServiceInterface\Data\ArticleRepository,
	Pi\ServiceInterface\Data\ArticleSerieRepository,
	Pi\ServiceInterface\Data\ArticleCategoryRepository,
	Pi\Extensions,
	Pi\Common\ClassUtils,
	Pi\ServiceModel\Types\FeedAction,
	Pi\ServiceInterface\UserFriendBusiness,
	Pi\ServiceInterface\UserFeedBusiness,
	Pi\ServiceModel\ArticleState,
	Pi\Odm\Query\QueryBuilder;


abstract class AbstractCreativeWorkService extends Service {

	protected ?string $articleRepoStr;
	protected ?string $categoryRepoStr;
	protected ?string $serieRepoStr;

	public function __construct(
		protected ArticleRepository $articleRepo,
		protected ArticleCategoryRepository $categoryRepo,
		protected ArticleSerieRepository $serieRepo,
		protected UserFriendBusiness $friendBus,
		protected UserFeedBusiness $feedBus
	)
	{
		parent::__construct();
		$this->init();
	}

	

	protected string $customType = 'creative-work';
	protected string $customTypeShort = 'cw';

	abstract function init();

	public function registerCustomType(string $type, ?string $typeShort = null)
	{
		$this->customType = $type;
		$this->customTypeShort = !is_null($typeShort) ? $typeShort : $type;
	}

	public function getCustomType() : string
	{
		return $this->customType;
	}

	public function getCustomTypeShort() : string
	{
		return $this->customTypeShort;
	}

	<<Request,Method('GET'),Route('/article/:id')>>
	public function get(GetArticleRequest $request)
	{
		$article = $this->articleRepo->getAs($request->getId(), 'Pi\ServiceModel\ArticleDto');

		if(is_null($article)) {
	    return HttpResult::notFound(sprintf(
	        'The Article with id %s wasn\'t found', $request->getId()
	    ));
    }
		$response = new GetArticleResponse();
		$response->setArticle($article);
		return $response;
	}

	<<Request,Method('GET'),Route('/article-category/:id')>>
	public function getCategory(GetArticleCategoryRequest $request)
	{
		$response = new GetArticleCategoryResponse();
		$dto = $this->categoryRepo->queryBuilder('Pi\ServiceModel\ArticleCategoryDto')
			->hydrate()
			->field('_id')->eq($request->getId())
			->getQuery()
			->getSingleResult();
		$response->setCategory($dto);
		return $response;
	}

	public static function getArticleId(string $displayName)
	{
		$trimmed = trim($displayName);
		$replaced = str_replace(' ', '-', $trimmed);
		return strtolower($replaced);
	}

	<<Request,Method('POST'),Route('/article-category')>>
	public function postCategory(PostArticleCategoryRequest $request)
	{
		$response = new PostArticleCategoryResponse();
		$dto = new ArticleCategoryDto();
		$entity = new ArticleCategory();

		if(!is_null($request->getParent())) {
			$parent = $this->categoryRepo->get($request->getParent());
			if(!is_null($parent)) {
				$n = ',' . $parent->getId() . ',';

				$path = $this->transformPath($parent->getId(), $parent->getPath());
				$entity->setPath($path);
			}
		}
				ClassUtils::mapDto($request, $entity);

		$id = Extensions::cleanString($request->getDisplayName());
		$id = Extensions::hyphenize($id);
		$entity->setId($id);

		$this->categoryRepo->insert($entity);

		ClassUtils::mapDto($entity, $dto);
		$response->setCategory($dto);

		return $response;
	}

	<<Request,Method('POST'),Route('/article-serie')>>
	public function postSerie(PostArticleSerieRequest $request)
	{
		$entity = new ArticleSerie();
		ClassUtils::mapDto($request, $entity);

		$entity->setArticles(array(array()));

		$this->serieRepo->insert($entity);

		$dto = new ArticleSerieDto();
		ClassUtils::mapDto($entity, $dto);
		$response = new PostArticleSerieResponse();
		$response->setSerie($dto);
		return $response;
	}

	<<Request,Method('POST'),Route('/article-serie-remove')>>
	public function removeSerie(RemoveArticleSerieRequest $request)
	{
		$this->serieRepo->remove($request->getId());
		$response = new RemoveArticleSerieResponse();
		return $response;
	}

	<<Request,Method('GET'),Route('/article-serie')>>
	public function findSeries(FindArticleSerieRequest $request)
	{
		$query = $this->serieRepo
			->queryBuilder('Pi\ServiceModel\ArticleSerieDto')
			->find()
			->hydrate()
			->limit($request->getLimit())
			->skip($request->getSkip());

		if(!is_null($request->getName())) {
			$query
				->field('name')->eq(new \MongoRegex("/$name/i"));
		}

		$data = $query
			->getQuery()
			->execute();

		$response = new FindArticleSerieResponse();
		if(is_array($data)) {
			$response->setSeries($data);
		}
	}

	<<Request,Method('GET'),Route('/article-serie/:id')>>
	public function getSerie(GetArticleSerieRequest $request)
	{
		$serie = $this->serieRepo
			->getAs($request->getId(), 'Pi\ServiceModel\ArticleSerieDto');

		$response = new GetArticleSerieResponse();
		if(!is_null($serie)) {
			$response->setSerie($serie);
		}
		return $response;
	}

	<<Request,Method('GET'),Route('/article-category')>>
	public function findCategory(FindArticleCategoryRequest $request)
	{
		$query = $this->articleRepo->queryBuilder('Pi\ServiceModel\ArticleCategoryDto')
			->find()
			->hydrate()
			->limit(100)
			->skip($request->getSkip());


		$categoryId = $request->getCategoryId();
		if(!is_null($categoryId)){
			$query
				->field('path')->eq(new \MongoRegex("/,$categoryId,/"));
		}

		$data = $query
			->getQuery()
			->execute();


		$response = new FindArticleCategoryResponse();
		$response->setCategories($data);
		return $response;
	}

	<<Request,Method('GET'),Route('/article')>>
	public function find(FindArticleRequest $request)
	{
		$query = $this->articleRepo->queryBuilder('Pi\ServiceModel\ArticleDto')
			->find()
			->hydrate()
			->limit($request->getLimit())
			->skip($request->getSkip())
			->sort('_id', -1);

		$categoryId = $request->getCategoryId();
		if(!is_null($categoryId)){
			$query->field('categoryPath')->eq(new \MongoRegex("/,$categoryId,/"));
		}

		$state = $request->getState();
		if(is_int($state)) {
			$query->field('state')->eq($request->getState());
		}
		
		$name = $request->getName();
		if(!is_null($name)) {
			$query->field('name')->eq(new \MongoRegex("/$name/i"));
		}

		$data = $query
			->getQuery()
			->execute();

		$count = $this->articleRepo->count();
		$this->response()->addHeader('X-Pi-Total', $count);
		$this->response()->addHeader('X-Pi-Pages', ceil($count / $request->getLimit()));
		$this->response()->addHeader('X-Pi-Page', ceil($count / $request->getLimit()));

		$response = new FindArticleResponse();
		$response->setArticles($data);
		return $response;
	}

	<<Request,Method('POST'),Route('/article-remove/:id')>>
	public function remove(RemoveArticleRequest $request)
	{
		$this->articleRepo->remove($request->getId());
		$response = new RemoveArticleResponse();
		return $response;
	}

	<<Request,Method('POST'),Route('/article-category-remove/:id')>>
	public function removeCategory(RemoveArticleCategoryRequest $request)
	{
		$this->categoryRepo->remove($request->getId());
		$response = new RemoveArticleCategoryResponse();
		return $response;
	}

	<<Request,Method('POST'),Route('/article')>>
	public function create(PostArticleRequest $request)
	{

		$response = new PostArticleResponse();
		$entity = new Article();
		ClassUtils::mapDto($request, $entity);

		if(is_null($request->getHeadline())) {
			$entity->setHeadline($request->getName());
		}

		if(isset($request->getCategoryId())) {
			$category = $this->categoryRepo->get($request->getCategoryId());
			if(!is_null($category)) {
				$path = $this->transformPath($category->getId(), $category->getPath());
				$entity->setCategoryPath($path);
				$embed = new ArticleCategoryEmbed();
				ClassUtils::mapDto($category, $embed);
				$entity->setCategory($embed);
			} else {
				throw new \Exception('category doesnt exists');
			}
		}

		if(isset($request->getSerieId())) {
			$serie = $this->serieRepo->get($request->getSerieId());
			$dto = new ArticleSerieEmbed();
			ClassUtils::mapDto($serie, $dto);
			$entity->setSerie($dto);
		}

		if(!is_int($request->getState())) {
			$entity->setState(ArticleState::Draft);
		}

		switch ($request->getState()) {
			case ArticleState::Published:
				$entity->setState(ArticleState::Published);
				$published = is_null($request->getDatePublished())
					? new \DateTime('now')
					: $request->getDatePublished();
				$entity->setDatePublished($published);
				break;
			default:
				
				break;
		}

		//if($request->getState() === ArticleState::Published) {

		//}

		$entity->setDateCreated(new \DateTime('now'));

		$author = $this->request()->author();
		$entity->setAuthor($author);
		$this->articleRepo->insert($entity);

		$url = $this->validateInputUrl($request->getUrl())
			? $request->getUrl()
			: Extensions::getUrl($this->appConfig(), $this->customTypeShort, $entity->getId(), $request->getName());
		$urlName = Extensions::getUrlName($request->getName());

		$builder = $this->articleRepo->queryBuilder();
		self::querySetUrl($builder, $entity->getId(), $url, $urlName);
		$builder
			->getQuery()
			->execute();

		$dto = new ArticleDto();
		ClassUtils::mapDto($entity, $dto);
		$response->setArticle($dto);

		$action = new FeedAction(
			$this->request()->getUserId(),
			new \DateTime('now'),
			false,
			'basic',
			'normal',
				array('title' => $entity->getName(), 'thumbnailSrc' => $entity->getImage(), 'id' => (string)$entity->id()),
			'article-new');

		$action->setAuthor($this->request->author());
		$this->feedBus->createPublic($action);

		return $response;
	}

	public static function querySetUrl(QueryBuilder &$builder, \MongoId $id, string $url, ?string $urlName)
	{
		$builder->update()
			->field('_id')->eq($id)
			->field('url')->set($url);

		if(!is_null($urlName)) {
			$builder->field('urlName')->set($urlName);
		}
	}

	<<Request,Method('POST'),Route('/article-reffer/:id')>>
	public function changeReffer(PostWorkReffer $request)
	{
		$up = $this->articleRepo->queryBuilder()
			->update()
			->field('_id')->eq($request->getId())
			->field('refferName')->set($request->getRefferName())
			->field('refferUrl')->set($request->getrefferUrl())
			->field('refferImage')->set($request->getRefferImage())
			->getQuery()
			->execute();

		$res = new PostWorkRefferResponse();
		return $res;
	}

	<<Request,Method('DELETE'),Route('/article-reffer/:id')>>
	public function removeReffer(RemoveWorkReffer $request)
	{
		$up = $this->articleRepo->queryBuilder()
			->update()
			->field('_id')->eq($request->getId())
			->field('refferName')->set(null)
			->field('refferUrl')->set(null)
			->field('refferImage')->set(null)
			->getQuery()
			->execute();

		$res = new RemoveWorkRefferResponse();
		return $res;
	}

	<<Request,Method('POST'),Route('/article-publish/:id')>>
	public function changePublishDate(PostWorkPublishDate $req)
	{

		$this->articleRepo->queryBuilder()
			->update()
			->field('_id')->eq($req->getId())
			->field('datePublished')->set($req->getDate())
			->getQuery()
			->execute();

		$res = new PostWorkPublishDateResponse();
		return $res;
	}


	<<Request,Method('POST'),Route('/article-save-category/:id')>>
	public function changeCategory(PostWorkCategory $req)
	{
		$category = $this->categoryRepo->get($req->getCategoryId());
		$path = $this->transformPath($category->getId(), $category->getPath());
		$embed = new ArticleCategoryEmbed();
		
		ClassUtils::mapDto($category, $embed);
		$this->articleRepo->queryBuilder()
			->update()
			->field('_id')->eq($req->getId())
			->field('categoryPath')->set($path)
			->field('category')->set($embed->jsonSerialize())
			->field('dateModified')->set(new \DateTime('now'))
			->getQuery()
			->execute();


		$res = new PostWorkCategoryResponse();
		return $res;
	}

	<<Request,Method('POST'),Route('/article-state/:id')>>
	public function changeState(PostWorkState $req)
	{
		$this->articleRepo->queryBuilder()
			->update()
			->field('_id')->eq($req->getId())
			->field('state')->set($req->getState())
			->field('dateModified')->set(new \DateTime('now'))
			->getQuery()
			->execute();


		$res = new PostWorkStateResponse();
		return $res;
	}

	<<Request,Method('POST'),Route('/article/:id')>>
	public function save(PutArticleRequest $request)
	{
		$response = new PutArticleResponse();
		$query = $this->articleRepo->queryBuilder()
			->update()
			->field('_id')->eq($request->getId())
			->field('name')->set($request->getName())
			->field('headline')->set($request->getHeadLine())
			->field('articleBody')->set($request->getArticleBody())
			->field('image')->set($request->getImage())
			->field('dateModified')->set(new \DateTime('now'));

		$query
			->getQuery()
			->execute();

		return $response;
	}

	<<Request,Method('POST'),Route('/article-keywords/:id')>>
	public function saveKeywords(PostArticleKeywordsRequest $request)
	{
		$response = new PostArticleKeywordsResponse();

		$res = $this->articleRepo
			->queryBuilder()
			->update()
			->field('_id')->eq($request->getId())
			->field('keywords')->push($request->getKeywords())
			->getQuery()
			->assertExecute();

		return $response;
	}

	<<Request,Method('DELETE'),Route('/article-keywords/:id')>>
	public function removeKeywords(PostArticleKeywordsRequest $request)
	{
		$response = new PostArticleKeywordsResponse();

		$res = $this->articleRepo
			->queryBuilder()
			->update()
			->field('_id')->eq($request->getId())
			->field('keywords')->push($request->getKeywords())
			->getQuery()
			->assertExecute();

		return $response;
	}

	<<Request,Method('POST'),Route('/article-normalize')>>
	public function normalizeAll(ArticleNormalizeAllRequest $request)
	{
		$r = HostProvider::execute(new FindArticleRequest());
		foreach ($r->getArticles() as $article) {

			$changes = array();
			if(is_null($article->getName()) || empty($article->getName())) {
				throw new \Exception('Article name shouldnt be null or empty');
			}

			if(!$this->validateInputUrl($article->getUrl())) {
				$changes['url'] = $this->getUrl((string)$article->getId(), $article->getName());
			}

			if(!$this->validateUrlName($article->getUrlName())) {
				$changes['urlName'] = Extensions::getUrlName($article->getName());
			}

			if(count($changes) > 0) {

				$query = $this->articleRepo
					->queryBuilder()
					->update()
					->upsert()
					->field('_id')->eq($article->getId());

				foreach ($changes as $changeKey => $changeValue) {
					$query = $query->field($changeKey)->set($changeValue);
				}

				$d = $query
					->getQuery()
					->execute();
			}
		}
		$response = new ArticleNormalizeAllResponse();
		return $response;
	}

	public function validateUrlName(?string $urlName)
	{
		return !is_null($urlName) && !empty($urlName);
	}

	protected function getUrl($id, string $title, ?string $path = null)
	{
		if(!is_null($path)) {
			$title = $title . '_' . $path;
		}
		return sprintf('%s//%s/%s/%s-%s', $this->appConfig()->protocol(), $this->appConfig()->domain(), $title, $this->customTypeShort, $id);
	}

	protected function transformPath(string $id, ?string $path)
	{
		$n = ',' . $id . ',';
		return is_null($path) ? $n : $parent->getPath() . $id . ',';
	}

	public static function formatPath(string $id, ?string $path = null)
	{
		$n = ',' . $id . ',';
		return is_null($path) ? $n : $parent->getPath() . $id . ',';	
	}

	protected function validateInputUrl(?string $url)
	{
		return !is_null($url) && is_string($url) && strlen($url) >= 5;
	}
}
