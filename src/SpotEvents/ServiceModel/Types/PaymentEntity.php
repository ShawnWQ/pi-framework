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
class PaymentEntity implements IEntity{

    public function id($value = null)
    {
        if($value === null) return $this->id;
        $this->id = $value;
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

    <<EmbedOne('Pi\ServiceModel\Types\Author')>>
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
    <<String>>
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return mixed
     */
    <<Int>>
    public function getSeed()
    {
        return $this->seed;
    }

    /**
     * @param mixed $seed
     */
    public function setSeed($seed)
    {
        $this->seed = $seed;
    }

    <<String>>
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

    protected Author $author;
}
