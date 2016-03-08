<?hh

namespace Pi\ServiceInterface;

use Pi\ServiceInterface\Data\QuestionRepository;
use Pi\ServiceInterface\Data\QuestionCategoryRepository;
use Pi\ServiceInterface\Data\AnswerRepository;
use Pi\ServiceModel\AnswerDto;
use Pi\ServiceModel\QuestionDto;
use Pi\ServiceModel\QuestionCategoryDto;
use Pi\ServiceModel\GetQuestionCategoryRequest;
use Pi\ServiceModel\GetQuestionCategoryResponse;
use Pi\ServiceModel\GetQuestionRequest;
use Pi\ServiceModel\GetQuestionResponse;
use Pi\ServiceModel\PostQuestionRequest;
use Pi\ServiceModel\PostQuestionResponse;
use Pi\ServiceModel\FindQuestionRequest;
use Pi\ServiceModel\FindQuestionResponse;
use Pi\ServiceModel\PostQuestionCategoryRequest;
use Pi\ServiceModel\PostQuestionCategoryResponse;
use Pi\ServiceModel\FindQuestionCategoryRequest;
use Pi\ServiceModel\FindQuestionCategoryResponse;
use Pi\ServiceModel\RemoveQuestionRequest;
use Pi\ServiceModel\RemoveQuestionResponse;
use Pi\ServiceModel\RemoveQuestionCategoryRequest;
use Pi\ServiceModel\RemoveQuestionCategoryResponse;
use Pi\ServiceModel\Types\Answer;
use Pi\ServiceModel\Types\Question;
use Pi\ServiceModel\Types\QuestionCategory;
use Pi\Service;
use Pi\Common\ClassUtils;

class QAService extends Service {

  public QuestionRepository $questionRepo;

  public AnswerRepository $answerRepo;

  public QuestionCategoryRepository $categoryRepo;


  public static function getQuestionId(string $displayName)
	{
		$trimmed = trim($displayName);
		$replaced = str_replace(' ', '-', $trimmed);
		return strtolower($replaced);
	}

  <<Request,Method('POST'),Route('/question-remove/:id')>>
  public function remove(RemoveQuestionRequest $request)
  {
    $this->questionRepo->remove($request->getId());
    $response = new RemoveQuestionResponse();
    return $response;
  }

  <<Request,Method('POST'),Route('/question-category-remove/:id')>>
  public function removeCategory(RemoveQuestionCategoryRequest $request)
  {
    $this->categoryRepo->remove($request->getId());
    $response = new RemoveQuestionCategoryResponse();
    return $response;
  }

  <<Request,Method('POST'),Route('/question')>>
  public function create(PostQuestionRequest $request)
  {
    $entity = new Question();
    ClassUtils::mapDto($request, $entity);

    if(isset($request->getCategoryId())) {
      $category = $this->categoryRepo->get($request->getCategoryId());
      if(!is_null($category)) {
        $path = $this->transformPath($category->getId(), $category->getPath());
        $entity->setCategoryPath($path);
      }
    }

    $this->questionRepo->insert($entity);

    $dto = new QuestionDto();
    ClassUtils::mapDto($entity, $dto);
    $response = new PostQuestionResponse();
    $response->setQuestion($dto);
    return $response;
  }

  <<Request,Method('GET'),Route('/question')>>
  public function find(FindQuestionRequest $request)
  {

    $query = $this->questionRepo->queryBuilder('Pi\ServiceModel\QuestionDto')
			->find()
			->hydrate()
			->limit($request->getLimit())
			->skip($request->getSkip());

		$categoryId = $request->getCategoryId();
		if(!is_null($categoryId)){
			$query
				->field('categoryPath')->eq(new \MongoRegex("/,$categoryId,/"));
		}

    $name = $request->getName();
    if(!is_null($name)) {
      $query->field('name')->eq(new \MongoRegex("/$name/"));
    }

		$data = $query
			->getQuery()
			->execute();
    $response = new FindQuestionResponse();
    $response->setQuestions($data);

    return $response;
  }

  <<Request,Method('GET'),Route('/question/:id')>>
  public function get(GetQuestionRequest $request)
  {
    $dto = $this->questionRepo->getAs($request->getId(), 'Pi\ServiceModel\QuestionDto');

    $response = new GetQuestionResponse();

    $response->setQuestion($dto);

    return $response;
  }

  <<Request,Method('GET'),Route('/question-category/:id')>>
  public function getCategory(GetQuestionCategoryRequest $request)
  {
    $dto = $this->categoryRepo->getAs($request->getId(), 'Pi\ServiceModel\QuestionCategoryDto');
    $response = new GetQuestionCategoryResponse();
    $response->setCategory($dto);
    return $response;
  }

  protected function transformPath(string $id, ?string $path)
  {
    $n = ',' . $id . ',';
    return is_null($path) ? $n : $parent->getPath() . $id . ',';
  }

  <<Request,Method('POST'),Route('/question-category')>>
  public function postCategory(PostQuestionCategoryRequest $request)
  {
    $response = new PostQuestionCategoryResponse();
    $dto = new QuestionCategoryDto();
    $entity = new QuestionCategory();

    ClassUtils::mapDto($request, $entity);

    if(!is_null($request->getParent())) {
      $parent = $this->categoryRepo->get($request->getParent());
      if(!is_null($parent)) {
        $n = ',' . $parent->getId() . ',';

        $path = $this->transformPath($parent->getId(), $parent->getPath());
        $entity->setPath($path);
      }
    }

    $id = self::getQuestionId($request->getDisplayName());
    $entity->setId($id);

    $this->categoryRepo->insert($entity);

    ClassUtils::mapDto($entity, $dto);
    $response->setCategory($dto);

    return $response;
  }

  <<Request,Method('GET'),Route('/question-category')>>
	public function findCategory(FindQuestionCategoryRequest $request)
	{
		$query = $this->categoryRepo->queryBuilder('Pi\ServiceModel\QuestionCategoryDto')
			->find()
			->hydrate()
			->limit($request->getLimit())
			->skip($request->getSkip());

		$categoryId = $request->getCategoryId();
		if(!is_null($categoryId)){
			$query
				->field('path')->eq(new \MongoRegex("/,$categoryId,/"));
		}

		$data = $query
			->getQuery()
			->execute();


		$response = new FindQuestionCategoryResponse();
		$response->setCategories($data);
		return $response;
	}

}
