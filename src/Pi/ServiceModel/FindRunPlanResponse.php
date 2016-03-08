<?hh

namespace Pi\ServiceModel;

use Pi\Response;

class FindRunPlanResponse extends Response {

  protected $plans;

  public function getPlans()
  {
    return $this->plans;
  }

  public function setPlans($plans)
  {
    $this->plans = $plans;
  }
}
