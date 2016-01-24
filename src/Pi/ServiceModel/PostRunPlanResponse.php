<?hh

namespace Pi\ServiceModel;

use Pi\Response;

class PostRunPlanResponse extends Response {

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
