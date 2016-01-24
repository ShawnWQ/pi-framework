<?hh

use Pi\ServiceModel\PostRunPlanRequest;
use Pi\ServiceModel\PostRunPlanResponse;
use Pi\ServiceModel\FindRunPlanRequest;
use Pi\ServiceModel\FindRunPlanResponse;
use Pi\ServiceModel\GetRunPlanRequest;
use Pi\ServiceModel\GetRunPlanResponse;
use Pi\ServiceModel\Types\RunPlan;
use Pi\ServiceInterface\Data\RunPlanRepository;
use Mocks\OdmContainer;
use Mocks\MockHostProvider;


class RunPlanServiceTest extends \PHPUnit_Framework_TestCase {


    protected RunPlanRepository $planRepo;

    public function setUp()
    {
      $ioc = OdmContainer::get();
      $this->planRepo = $ioc->get('Pi\ServiceInterface\Data\RunPlanRepository');
    }

    public function testCanCreateRunPlan()
    {
      $request = new PostRunPlanRequest();
      $request->setTitle('dumb title');
      $request->setDescription('asdas dasdasd');

      $res = MockHostProvider::execute($request);

      $this->assertEquals($res->getPlan()->getTitle(), $request->getTitle());
    }

    public function testCanGetRunPlanById()
    {
      $plan = $this->createRunPlan('222');
      $request = new GetRunPlanRequest();
      $request->setPlanId($plan->id());

      $res = MockHostProvider::execute($request);

      $this->assertEquals($plan->getTitle(), $res->getPlan()->getTitle());
    }


    public function testCanFindRunPlan()
    {
      $this->createRunPlan('222');
      $request = new FindRunPlanRequest();

      $res = MockHostProvider::execute($request);

      $this->assertTrue(count($res->getPlans()) > 0);
    }


    protected function createRunPlan(string $title = 'asdasd') : RunPlan
    {
      $entity = new RunPlan();
      $entity->setTitle($title);
      $entity->setDescription('asdasdasdasd');

      $this->planRepo->insert($entity);
      return $entity;
    }
}
