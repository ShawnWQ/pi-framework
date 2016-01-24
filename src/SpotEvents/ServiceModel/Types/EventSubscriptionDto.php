<?hh

namespace SpotEvents\ServiceModel\Types;
use Pi\Odm\Interfaces\IBucketCollection;
use Pi\Odm\Interfaces\IEntity;

<<Entity,Collection("EventSubscription")>>
class EventSubscriptionDto implements IBucketCollection, IEntity, \JsonSerializable {

    public function jsonSerialize()
    {
        $arr = get_object_vars($this);
        $arr['id'] = (string)$arr['id'];
        $arr['paymentId'] = (string)$arr['paymentId'];
        $arr['entityId'] = (string)$arr['entityId'];
        return $arr;
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
    <<ObjectId>>
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * @param mixed $paymentId
     */
    public function setPaymentId($paymentId)
    {
        $this->paymentId = $paymentId;
    }

    protected $id;

    protected $position;

    protected $limit;

    protected $entityId;

    protected $entityName;

    protected $amount;

    protected $payment;

    protected \MongoId $paymentId;

<<Int>>
    public function getPosition()
    {

    }

<<Int>>
    public function getLimit()
    {

    }

<<Embed>>
    public function getPayment()
    {
        return $this->payment;
    }

    public function setPayment($dto)
    {
        $this->payment = $dto;
    }

<<ObjectId>>
    public function getEntityId()
    {
        return $this->entityId;
    }

<<String>>
    public function getEntityName()
    {
        return $this->entityName;
    }

<<Id>>
    public function id($value = null)
    {
        if($value === null) return $this->id;
        $this->id = $value;
    }

<<ObjectId>>
    public function paymentId($value = null)
    {
        if($value === null) return $this->paymentId;
        $this->paymentId = $value;
    }

<<ReferenceOne('SpotEvents\Types\Event')>>
    public function entityId($value = null)
    {
        if($value === null) return $this->entityId;
        $this->entityId = $value;
    }
}
