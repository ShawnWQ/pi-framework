<?hh
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 6/21/15
 * Time: 1:45 AM
 */

namespace Pi\ServiceModel;


class PostEventLikeRequest {

    protected \MongoId $eventId;

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
}
