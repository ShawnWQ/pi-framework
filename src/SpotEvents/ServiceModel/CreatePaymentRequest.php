<?hh

namespace SpotEvents\ServiceModel;

class CreatePaymentRequest {
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
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return mixed
     */
    public function getSubEntity()
    {
        return $this->subEntity;
    }

    /**
     * @param mixed $subEntity
     */
    public function setSubEntity($subEntity)
    {
        $this->subEntity = $subEntity;
    }

    /**
     * @return mixed
     */
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

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle(string $value)
    {
        $this->title = $value;
    }

	protected $entity;

    protected $title;

	protected $subEntity;

	protected $amount;

}