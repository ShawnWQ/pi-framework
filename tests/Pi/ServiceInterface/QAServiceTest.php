<?hh

use Mocks\OdmContainer;
use Mocks\MockHostProvider;
use Mocks\AuthMock;
use Pi\ServiceInterface\QAService;
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
use Pi\Common\RandomString;

class QAServiceTest extends \PHPUnit_Framework_TestCase {

	protected QuestionRepository $questionRepo;

	protected QuestionCategoryRepository $categoryRepo;

  protected AnswerCategoryRepository $answerRepo;

	public function setUp()
	{
		$container = OdmContainer::get();
		$this->answerRepo = $container->get('Pi\ServiceInterface\Data\AnswerRepository');
    $this->questionRepo = $container->get('Pi\ServiceInterface\Data\QuestionRepository');
		$this->categoryRepo = $container->get('Pi\ServiceInterface\Data\QuestionCategoryRepository');
	}

	public function testGetId()
	{
		$name = 'Teste De Nome';
		$id = QAService::getQuestionId($name);
		$this->assertEquals($id, 'teste-de-nome');
	}

	public function testCanGetQuestion()
	{
		$q = $this->createQuestion('asdasd asd');
		$req = new GetQuestionRequest();
    $req->setId($q->id());

		$res = MockHostProvider::execute($req);
		$this->assertEquals($res->getQuestion()->getName(), $q->getName());
	}

  public function testCanFindQuestion()
	{
		$this->createQuestion('asdasd asd');
		$req = new FindQuestionRequest();
		$res = MockHostProvider::execute($req);

		$this->assertTrue(count($res->getQuestions()) > 0);
	}

	public function testCanFindArticleByCategoryDescendants()
	{
		$cat = $this->createCategory();

		$req = new FindQuestionRequest();
		$req->setCategoryId($cat->getId());
		$res = MockHostProvider::execute($req);

		$this->assertTrue(count($res->getQuestions()) === 0);

		$this->createQuestion('asdasd asd', $cat->getId());
		$req = new FindQuestionRequest();
		$req->setCategoryId($cat->getId());
		$res = MockHostProvider::execute($req);
		$this->assertTrue(count($res->getQuestions()) === 1);
	}

	public function testCanFindQuestionByName()
	{
		$rand = RandomString::generate(15);
		$art = $this->createQuestion('the first words ' . $rand);
		$req = new FindQuestionRequest();
		$req->setName(RandomString::generate(50));

		$res = MockHostProvider::execute($req);
		$this->assertTrue(count($res->getQuestions()) === 0);

		$req->setName('the first words');
		$res = MockHostProvider::execute($req);
		$this->assertTrue(count($res->getQuestions()) > 0);

		$req->setName('the first words ' . $rand);
		$res = MockHostProvider::execute($req);
		$this->assertTrue(count($res->getQuestions()) === 1);
	}

	public function testCanCreateQuestion()
	{
		$req = new PostQuestionRequest();

		$req->setName('asdasd');

		$res = MockHostProvider::execute($req);

		$this->assertEquals($res->getQuestion()->getName(), $req->getName());
	}

	public function testCreateArticleAndCategoryPath()
	{
		$req = new PostQuestionRequest();
		$req->setName('asdasdasd');
		$cat = $this->createCategory();
		$req->setCategoryId($cat->getId());

		$res = MockHostProvider::execute($req);
		$this->assertEquals($res->getQuestion()->getCategoryPath(), ',' . $cat->getId() . ',');
	}

	public function testCanCreateCategory()
	{
		$req = new PostQuestionCategoryRequest();
		$req->setDisplayName(RandomString::generate());
		$parent = $this->createCategory();
		$req->setParent($parent->getId());
		$res = MockHostProvider::execute($req);

		$this->assertEquals($res->getCategory()->getDisplayName(), $req->getDisplayName());
	}

	public function testCanGetCategory()
	{
		$req = new GetQuestionCategoryRequest();
		$cat = $this->createCategory();
		$req->setId($cat->id());

		$res = MockHostProvider::execute($req);
		$this->assertEquals($res->getCategory()->getDisplayName(), $cat->getDisplayName());
	}

	public function testCanFindCategory()
	{
		$req = new FindQuestionCategoryRequest();
		$cat = $this->createCategory();


		$res = MockHostProvider::execute($req);
		$this->assertTrue(count($res->getCategories()) > 0);
	}

	public function testCanRemoveQuestionById()
	{
		$art = $this->createQuestion();

		$req = new RemoveQuestionRequest();
		$req->setId($art->getId());
		MockHostProvider::execute($req);

		$artDb = $this->questionRepo->get($art->getId());
		$this->assertTrue(is_null($artDb));
	}

	public function testCanRemoveQuestionCategoryById()
	{
		$cat = $this->createCategory();

		$req = new RemoveQuestionCategoryRequest();
		$req->setId($cat->getId());
		MockHostProvider::execute($req);

		$catDb = $this->categoryRepo->get($cat->getId());
		$this->assertTrue(is_null($catDb));
	}

	protected function createQuestion($name = 'test user', $catId = null)
	{
		$entity = new Question();
		if(!is_null($catId)) {
			$entity->setCategoryPath(',' . $catId . ',');
		}
    $entity->setName($name);
    $entity->setQuestionBody('asdasdasd');
		$entity->setHeadline('headline');

		$this->questionRepo->insert($entity);
		return $entity;
	}

	protected function createCategory($name = 'test user')
	{
		$entity = new QuestionCategory();
		$entity->setId(RandomString::generate());
    $entity->setDisplayName(RandomString::generate());

		$this->categoryRepo->insert($entity);
		return $entity;
	}
}
