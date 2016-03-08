<?hh
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 6/21/15
 * Time: 1:45 AM
 */

namespace Pi\ServiceModel;


class PostPlaceLikeRequest {

    protected \MongoId $placeId;

    /**
     * @return mixed
     */
    <<ObjectId>>
    public function getPlaceId()
    {
        return $this->placeId;
    }

    /**
     * @param mixed $eventId
     */
    public function setPlaceId($placeId)
    {
        $this->placeId = $placeId;
    }
}