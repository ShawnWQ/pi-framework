<?hh

namespace SpotEvents\ServiceModel;

class GetEventAvatarCardRequest {
	
	protected $userId;

	protected $eventId;


    /**
     * Gets the value of userId.
     *
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Sets the value of userId.
     *
     * @param mixed $userId the user id
     *
     * @return self
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Gets the value of eventId.
     *
     * @return mixed
     */
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
}