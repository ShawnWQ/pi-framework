<?hh
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 6/18/15
 * Time: 5:29 AM
 */

namespace SpotEvents\ServiceModel\Types;


use Pi\Odm\Interfaces\IEntity;

<<Entity,Collection('gymn_campaign')>>
class GymCampaignDto implements IEntity, \JsonSerializable{

    public function jsonSerialize()
    {
        $ar = get_object_vars($this);
        $ar['id'] = (string)$ar['id'];
        return $ar;
    }

<<Id>>
    public function id($value = null)
    {
        if($value === null) { return $this->id;}
        $this->id = $value;
    }
<<String>>
    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
<<Int>>
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getCampaignDuration()
    {
        return $this->campaignDuration;
    }

    /**
     * @param mixed $campaignDuration
     */
    public function setCampaignDuration($campaignDuration)
    {
        $this->campaignDuration = $campaignDuration;
    }
<<Datetime>>
    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
<<Datetime>>
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * @return mixed
     */
<<String>>
    public function getContractDuration()
    {
        return $this->contractDuration;
    }

    /**
     * @param mixed $contractDuration
     */
    public function setContractDuration($contractDuration)
    {
        $this->contractDuration = $contractDuration;
    }

    /**
     * @return mixed
     */
<<String>>
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    protected $title;

    protected $amount;

    protected $campaignDuration;

    protected $startDate;

    protected $endDate;

    protected $contractDuration;

    protected $description;

    protected \MongoId $id;
}