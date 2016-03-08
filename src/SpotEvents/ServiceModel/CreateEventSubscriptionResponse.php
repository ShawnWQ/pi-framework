<?hh

namespace SpotEvents\ServiceModel;
use Pi\Response;

class CreateEventSubscriptionResponse extends Response {
	
	/**
	 * @var \MongoId
	 */
	protected $subscriptionId;

	protected $subscription;
		
	public function getSubscriptionId()
	{
		return $this->subscriptionId;
	}

	public function setSubscriptionId(\MongoId $value)
	{
		$this->subscriptionId = $value;;
	}

	public function setSubscription($dto)
	{
		$this->subscription = $dto;
	}

	public function getSubscription()
	{
		return $this->subscription;
	}
}