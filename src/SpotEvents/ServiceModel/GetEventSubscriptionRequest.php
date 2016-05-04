<?hh

namespace SpotEvents\ServiceModel;

class GetEventSubscriptionRequest {
	
	protected \MongoId $eventId;

	protected $subscriptionId;

    /**
     * Gets the value of eventId.
     *
     * @return mixed
     */
    <<ObjectId>>
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * Sets the value of eventId.
     *
     * @param mixed $eventId the event id
     *
     * @return self
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;

        return $this;
    }

    /**
     * Gets the value of subscriptionId.
     *
     * @return mixed
     */
<<ObjectId>>
    public function getSubscriptionId()
    {
        return $this->subscriptionId;
    }

    /**
     * Sets the value of subscriptionId.
     *
     * @param mixed $subscriptionId the subscription id
     *
     * @return self
     */
    public function setSubscriptionId($subscriptionId)
    {
        $this->subscriptionId = $subscriptionId;

        return $this;
    }
}