<?hh
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 6/21/15
 * Time: 1:46 AM
 */

namespace Pi\ServiceModel;


class GetEventLikesRequest {
    /**
     * @return mixed
     */
    <<ObjectId>>
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * @param mixed $eventId
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;
    }

    protected \MongoId $eventId;
}