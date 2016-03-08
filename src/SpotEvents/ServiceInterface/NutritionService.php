<?hh

namespace SpotEvents\ServiceInterface;

use Pi\Service;
use Pi\HttpResult;
use Pi\Common\ClassUtils;
use SpotEvents\ServiceInterface\Data\NutritionRepository;
use SpotEvents\ServiceInterface\Data\NutritionSerieRepository;
use SpotEvents\ServiceModel\PostNutritionRequest;
use SpotEvents\ServiceModel\PostNutritionResponse;
use SpotEvents\ServiceModel\GetNutritionRequest;
use SpotEvents\ServiceModel\GetNutritionResponse;
use SpotEvents\ServiceModel\FindNutritionRequest;
use SpotEvents\ServiceModel\FindNutritionResponse;
use SpotEvents\ServiceModel\RemoveNutritionRequest;
use SpotEvents\ServiceModel\RemoveNutritionResponse;
use SpotEvents\ServiceModel\GetNutritionSerieRequest;
use SpotEvents\ServiceModel\GetNutritionSerieResponse;
use SpotEvents\ServiceModel\RemoveNutritionSerieRequest;
use SpotEvents\ServiceModel\RemoveNutritionSerieResponse;
use SpotEvents\ServiceModel\PostNutritionSerieRequest;
use SpotEvents\ServiceModel\PostNutritionSerieResponse;
use SpotEvents\ServiceModel\FindNutritionSerieRequest;
use SpotEvents\ServiceModel\FindNutritionSerieResponse;
use SpotEvents\ServiceModel\NutritionDto;
use SpotEvents\ServiceModel\NutritionSerieDto;
use SpotEvents\ServiceModel\Types\NutritionPlan;
use SpotEvents\ServiceModel\Types\NutritionSerie;
use Pi\ServiceModel\Types\FeedAction;
use Pi\ServiceModel\Types\ArticleSerieEmbed;
use Pi\ServiceInterface\UserFriendBusiness;
use Pi\ServiceInterface\UserFeedBusiness;


class NutritionService extends Service {

    public NutritionRepository $nutritionRepo;

    public UserFriendBusiness $friendBus;

    public UserFeedBusiness $feedBus;

    public NutritionSerieRepository $serieRepo;

    <<Request,Route('/nutrition/:id'),Method('POST')>>
    public function post(PostNutritionRequest $request)
    {
      $entity = new NutritionPlan();
      ClassUtils::mapDto($request, $entity);
      $entity->setDatePublished(new \Datetime('now'));
      $entity->setAuthor($this->request()->author());
      $entity->setWordCount(str_word_count($request->getArticleBody()));

      if(isset($request->getSerieId())) {
  			$serie = $this->serieRepo->get($request->getSerieId());
  			$dto = new ArticleSerieEmbed();
  			ClassUtils::mapDto($serie, $dto);
  			$entity->setSerie($dto->jsonSerialize());
  		}

      $this->nutritionRepo->insert($entity);

      $dto = new NutritionDto();
      ClassUtils::mapDto($entity, $dto);
      $response = new PostNutritionResponse();
      $response->setNutrition($dto);

      $action = new FeedAction(
  			$this->request()->getUserId(),
  			new \DateTime('now'),
  			false,
  			'basic',
  			'normal',
  				array('title' => $entity->getName(), 'thumbnailSrc' => $entity->getImage(), 'id' => (string)$entity->id()),
  			'nutrition-new');

        $action->setAuthor($this->request->author());
        $this->feedBus->createPublic($action);
      return $response;
    }

    <<Request,Route('/nutrition'),Method('GET')>>
    public function find(FindNutritionRequest $request)
    {
      $data = $this->nutritionRepo->
        queryBuilder('SpotEvents\ServiceModel\NutritionDto')
        ->find()
        ->hydrate()
        ->limit($request->getLimit())
        ->skip($request->getSkip())
        ->getQuery()
        ->execute();

      $response = new FindNutritionResponse();
      $response->setNutritions($data);
      return $response;
    }

    <<Request,Route('/nutrition/:id')>>
    public function get(GetNutritionRequest $request)
    {
      $response = new GetNutritionResponse();
      $dto = $this->nutritionRepo->getAs($request->getId(), 'SpotEvents\ServiceModel\NutritionDto');
      if(is_null($dto)) {
            return HttpResult::notFound(sprintf(
                'The Plan Nutrition with id %s wasn\'t found', $request->getId()
            ));
        }
      $response->setNutrition($dto);
      return $response;
    }

    <<Request,Method('POST'),Route('/nutrition-remove/:id')>>
    public function remove(RemoveNutritionRequest $request)
    {
      $this->nutritionRepo->remove($request->getId());
      $response = new RemoveNutritionResponse();
      return $response;
    }

    <<Request,Method('POST'),Route('/nutrition-serie-create')>>
  	public function postSerie(PostNutritionSerieRequest $request)
  	{
  		$entity = new NutritionSerie();
  		ClassUtils::mapDto($request, $entity);

  		$entity->setArticles(array(array()));

  		$this->serieRepo->insert($entity);

  		$dto = new NutritionSerieDto();
  		ClassUtils::mapDto($entity, $dto);
  		$response = new PostNutritionSerieResponse();
  		$response->setSerie($dto);
  		return $response;
  	}

  	<<Request,Method('POST'),Route('/nutrition-serie-remove')>>
  	public function removeSerie(RemoveNutritionSerieRequest $request)
  	{
  		$this->serieRepo->remove($request->getId());
  		$response = new RemoveNutritionSerieResponse();
  		return $response;
  	}

  	<<Request,Method('GET'),Route('/nutrition-serie')>>
  	public function findSeries(FindNutritionSerieRequest $request)
  	{
  		$query = $this->serieRepo
  			->queryBuilder('SpotEvents\ServiceModel\NutritionSerieDto')
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

  		$response = new FindNutritionSerieResponse();
  		if(is_array($data)) {
  			$response->setSeries($data);
  		}

      return $response;
  	}

  	<<Request,Method('GET'),Route('/nutrition-serie/:id')>>
  	public function getSerie(GetNutritionSerieRequest $request)
  	{
  		$serie = $this->serieRepo
  			->getAs($request->getId(), 'SpotEvents\ServiceModel\NutritionSerieDto');

      $data = $this->nutritionRepo
        ->queryBuilder('SpotEvents\ServiceModel\NutritionDto')
        ->find()
        ->field('serie.id')->eq($request->getId())
        ->hydrate()
        ->limit(20)
        ->skip(0)
        ->getQuery()
        ->execute();

  		$response = new GetNutritionSerieResponse();
  		if(!is_null($serie)) {
  			$response->setSerie($serie);
  		}

  		return $response;
  	}

}
