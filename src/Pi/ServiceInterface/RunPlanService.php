<?hh

namespace Pi\ServiceInterface;

use Pi\Service;
use Pi\ServiceModel\PostRunPlanRequest;
use Pi\ServiceModel\PostRunPlanResponse;
use Pi\ServiceModel\FindRunPlanRequest;
use Pi\ServiceModel\FindRunPlanResponse;
use Pi\ServiceModel\GetRunPlanRequest;
use Pi\ServiceModel\GetRunPlanResponse;
use Pi\ServiceInterface\Data\RunPlanRepository;
use Pi\ServiceModel\RunPlanDto;
use Pi\ServiceModel\Types\RunPlan;
use Pi\Common\ClassUtils;


class RunPlanService extends Service {

  public RunPlanRepository $planRepo;

  <<Request,Route('/run-plan'),Method('POST')>>
  public function post(PostRunPlanRequest $request)
  {
    $response = new PostRunPlanResponse();

    $entity = new RunPlan();
    ClassUtils::mapDto($request, $entity);
    $entity->setUser($this->request()->author());
    $this->planRepo->insert($entity);
    $dto = new RunPlanDto();
    ClassUtils::mapDto($entity, $dto);
    $response->setPlan($dto);
    return $response;

  }

  <<Request,Route('/run-plan/:planId'),Method('GET')>>
  public function get(GetRunPlanRequest $request)
  {
    $response = new GetRunPlanResponse();
    $dto = $this->planRepo->getAs($request->getPlanId(), 'Pi\ServiceModel\RunPlanDto');

    $response->setPlan($dto);
    return $response;
  }

  <<Request,Route('/run-plan'),Method('GET')>>
  public function find(FindRunPlanRequest $request)
  {
    $response = new FindRunPlanResponse();

    $data = $this->planRepo
      ->queryBuilder('Pi\ServiceModel\RunPlanDto')
      ->find()
      ->hydrate(true)
      ->getQuery()
      ->execute();

    if(is_array($data)) {
      $response->setPlans($data);
    }

    return $response;
  }
}
