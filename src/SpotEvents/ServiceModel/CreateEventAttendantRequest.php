<?hh

namespace SpotEvents\ServiceModel;

class CreateEventAttendantRequest {
	
	/**
	 * @var \MongoId
	 */
	protected \MongoId $eventId;


    /**
     * Gets the value of eventId.
     *
     * @return \MongoId
     */
    <<ObjectId>>
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * Sets the value of eventId.
     *
     * @param \MongoId $eventId the event id
     *
     * @return self
     */
    public function setEventId(\MongoId $eventId)
    {
        $this->eventId = $eventId;

        return $this;
    }
}