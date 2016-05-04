<?hh

namespace Pi\ServiceModel;

use Pi\Response;

class GetRunPlanResponse extends Response {

  protected $plan;

  public function getPlan()
  {
    return $this->plan;
  }

  public function setPlan($dto)
  {
    $this->plan = $dto;
  }
}
