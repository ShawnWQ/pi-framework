<?hh

namespace Pi\ServiceModel;

class GetRunPlanRequest {

	protected \MongoId $planId;

	<<ObjectId>>
	public function getPlanId()
	{
		return $this->planId;
	}

	public function setPlanId(\MongoId $id)
	{
		$this->planId = $id;
	}
}
