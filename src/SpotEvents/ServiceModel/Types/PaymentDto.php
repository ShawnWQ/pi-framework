<?hh
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 6/9/15
 * Time: 4:35 AM
 */

namespace SpotEvents\ServiceModel\Types;
use Pi\Odm\Interfaces\IEntity;
use Pi\ServiceModel\Types\Author;

<<Entity,Collection('PaymentEntity')>>
class PaymentDto implements \JsonSerializable, IEntity {
    
    <<EmbedOne('Pi\ServiceModel\TypezAuthor')>>
    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    <<Int>>
    public function getSubscriptionAmount()
    {
        return $this->subscriptionAmount;
    }

    /**
     * @param mixed $subscriptionAmount
     */
    public function setSubscriptionAmount($subscriptionAmount)
    {
        $this->subscriptionAmount = $subscriptionAmount;
    }

    public function jsonSerialize()
    {
        $arr = get_object_vars($this);
        $arr['id'] = (string)$arr['id'];
        return $arr;
    }

    /**
     * @return mixed
     */
    <<Id>>
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

    public function id($id = null)
    {
        if(is_null($id)) return $this->id;
        $this->id = $id;
    }

    /**
     * @return mixed
     */
<<String>>
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
<<String>>
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
<<String>>
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param mixed $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     */
<<String>>
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return mixed
     */
    public function getSeed()
    {
        return $this->seed;
    }

    /**
     * @param mixed $seed
     */
    <<Int>>
    public function setSeed($seed)
    {
        $this->seed = $seed;
    }

    <<Int>>
    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($value)
    {
        $this->status = $value;
    }

    protected $id;

    protected $title;

    protected $amount;

    protected PaymentStatus $status;

    protected $reference;

    protected $entity;

    protected $seed;

    protected $subscriptionAmount;

    protected Author $author;
}