<?hh

namespace SpotEvents\ServiceModel\Types;
use Pi\Odm\Interfaces\IBucketCollection;
use Pi\Odm\Interfaces\IEntity;

class EventSubscription implements IEntity {

  /**
   * @var \MongoId
   */
  protected $id;

  protected $entityId;

  protected $entityName;

  protected $amount;

  protected $payment;

  protected $paymentId;

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

  <<ObjectId>>
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

  <<ObjectId>>
  public function entityId($value = null)
  {
    if($value === null) return $this->entityId;
    $this->entityId = $value;
  }
}
