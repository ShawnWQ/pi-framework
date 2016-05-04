<?hh

namespace SpotEvents\ServiceModel;

class GetEventAttendantRequest {

	protected \MongoId $eventId;

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
}