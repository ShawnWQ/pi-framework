<?hh

namespace SpotEvents\ServiceModel;
use Pi\Response;

class GetEventSubscriptionResponse extends Response {

	protected $subscription;

    protected $payment;

    public function getPayment()
    {
        return $this->payment;
    }

    public function setPayment($value)
    {
        $this->payment = $value;
    }

    /**
     * Gets the value of subscription.
     *
     * @return mixed
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * Sets the value of subscription.
     *
     * @param mixed $subscription the subscription
     *
     * @return self
     */
    public function setSubscription($subscription)
    {
        $this->subscription = $subscription;

        return $this;
    }
}