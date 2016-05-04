<?hh

namespace SpotEvents\ServiceInterface;

use Pi\Service;
use Pi\HttpResult;
use Pi\Common\ClassUtils;
use SpotEvents\ServiceInterface\Data\WorkoutRepository;
use SpotEvents\ServiceInterface\Data\WorkoutSerieRepository;
use SpotEvents\ServiceModel\PostWorkoutRequest;
use SpotEvents\ServiceModel\PostWorkoutResponse;
use SpotEvents\ServiceModel\GetWorkoutRequest;
use SpotEvents\ServiceModel\GetWorkoutResponse;
use SpotEvents\ServiceModel\FindWorkoutRequest;
use SpotEvents\ServiceModel\FindWorkoutResponse;
use SpotEvents\ServiceModel\RemoveWorkoutRequest;
use SpotEvents\ServiceModel\RemoveWorkoutResponse;
use SpotEvents\ServiceModel\GetWorkoutSerieRequest;
use SpotEvents\ServiceModel\GetWorkoutSerieResponse;
use SpotEvents\ServiceModel\RemoveWorkoutSerieRequest;
use SpotEvents\ServiceModel\RemoveWorkoutSerieResponse;
use SpotEvents\ServiceModel\PostWorkoutSerieRequest;
use SpotEvents\ServiceModel\PostWorkoutSerieResponse;
use SpotEvents\ServiceModel\FindWorkoutSerieRequest;
use SpotEvents\ServiceModel\FindWorkoutSerieResponse;
use SpotEvents\ServiceModel\WorkoutDto;
use SpotEvents\ServiceModel\WorkoutSerieDto;
use SpotEvents\ServiceModel\Types\Workout;
use SpotEvents\ServiceModel\Types\WorkoutSerie;
use Pi\ServiceModel\Types\FeedAction;
use Pi\ServiceModel\Types\ArticleSerieEmbed;
use Pi\ServiceInterface\UserFriendBusiness;
use Pi\ServiceInterface\UserFeedBusiness;


class WorkoutService extends Service {

    public WorkoutRepository $workoutRepo;

    public UserFriendBusiness $friendBus;

    public UserFeedBusiness $feedBus;

    public WorkoutSerieRepository $serieRepo;

    <<Request,Route('/workout'),Method('POST')>>
    public function post(PostWorkoutRequest $request)
    {
      $entity = new Workout();
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

      $this->workoutRepo->insert($entity);

      $dto = new WorkoutDto();
      ClassUtils::mapDto($entity, $dto);
      $response = new PostWorkoutResponse();
      $response->setWorkout($dto);

      $action = new FeedAction(
  			$this->request()->getUserId(),
  			new \DateTime('now'),
  			false,
  			'basic',
  			'normal',
  				array('title' => $entity->getName(), 'thumbnailSrc' => $entity->getImage(), 'id' => (string)$entity->id()),
  			'workout-new');

        $action->setAuthor($this->request->author());
        $this->feedBus->createPublic($action);
      return $response;
    }

    <<Request,Route('/workout'),Method('GET')>>
    public function find(FindWorkoutRequest $request)
    {
      $data = $this->workoutRepo->
        queryBuilder('SpotEvents\ServiceModel\WorkoutDto')
        ->find()
        ->hydrate()
        ->limit($request->getLimit())
        ->skip($request->getSkip())
        ->getQuery()
        ->execute();

      $response = new FindWorkoutResponse();
      $response->setWorkouts($data);
      return $response;
    }

    <<Request,Route('/workout/:id')>>
    public function get(GetWorkoutRequest $request)
    {
      $response = new GetWorkoutResponse();
      $dto = $this->workoutRepo->getAs($request->getId(), 'SpotEvents\ServiceModel\WorkoutDto');
      if(is_null($dto)) {
            return HttpResult::notFound(sprintf(
                'The Plan Workout with id %s wasn\'t found', $request->getId()
            ));
        }
      $response->setWorkout($dto);
      return $response;
    }

    <<Request,Method('POST'),Route('/workout-remove/:id')>>
    public function remove(RemoveWorkoutRequest $request)
    {
      $this->workoutRepo->remove($request->getId());
      $response = new RemoveWorkoutResponse();
      return $response;
    }

    <<Request,Method('POST'),Route('/workout-serie')>>
  	public function postSerie(PostWorkoutSerieRequest $request)
  	{
  		$entity = new WorkoutSerie();
  		ClassUtils::mapDto($request, $entity);

  		$entity->setArticles(array(array()));

  		$this->serieRepo->insert($entity);

  		$dto = new WorkoutSerieDto();
  		ClassUtils::mapDto($entity, $dto);
  		$response = new PostWorkoutSerieResponse();
  		$response->setSerie($dto);
  		return $response;
  	}

  	<<Request,Method('POST'),Route('/workout-serie-remove')>>
  	public function removeSerie(RemoveWorkoutSerieRequest $request)
  	{
  		$this->serieRepo->remove($request->getId());
  		$response = new RemoveWorkoutSerieResponse();
  		return $response;
  	}

  	<<Request,Method('GET'),Route('/workout-serie')>>
  	public function findSeries(FindWorkoutSerieRequest $request)
  	{
  		$query = $this->serieRepo
  			->queryBuilder('SpotEvents\ServiceModel\WorkoutSerieDto')
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

  		$response = new FindWorkoutSerieResponse();
  		if(is_array($data)) {
  			$response->setSeries($data);
  		}

      return $response;
  	}

  	<<Request,Method('GET'),Route('/workout-serie/:id')>>
  	public function getSerie(GetWorkoutSerieRequest $request)
  	{
  		$serie = $this->serieRepo
  			->getAs($request->getId(), 'SpotEvents\ServiceModel\WorkoutSerieDto');

  		$response = new GetWorkoutSerieResponse();
  		if(!is_null($serie)) {
  			$response->setSerie($serie);
  		}
  		return $response;
  	}

}
