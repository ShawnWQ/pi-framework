<?hh
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 6/18/15
 * Time: 5:58 AM
 */

namespace SpotEvents\ServiceModel;


use Pi\Response;

class GetGymCampaignResponse extends Response{
    /**
     * @return mixed
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * @param mixed $campaign
     */
    public function setCampaign($campaign)
    {
        $this->campaign = $campaign;
    }

    protected $campaign;
}