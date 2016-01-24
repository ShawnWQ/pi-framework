<?php
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 6/18/15
 * Time: 5:49 AM
 */

namespace SpotEvents\ServiceModel;


use Pi\Response;

class FindGymCampaignResponse extends Response{
    /**
     * @return mixed
     */
    public function getCampaigns()
    {
        return $this->campaigns;
    }

    /**
     * @param mixed $campaigns
     */
    public function setCampaigns($campaigns)
    {
        $this->campaigns = $campaigns;
    }

    protected $campaigns;
}