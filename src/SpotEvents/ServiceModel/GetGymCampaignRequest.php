<?hh
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 6/18/15
 * Time: 5:57 AM
 */

namespace SpotEvents\ServiceModel;


class GetGymCampaignRequest {
    /**
     * @return mixed
     */
    <<ObjectId>>
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    protected \MongoId $id;
}