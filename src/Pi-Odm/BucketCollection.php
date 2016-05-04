<?hh

namespace Pi\Odm;

use Pi\Odm\Interfaces\IBucketCollection;
use Pi\Odm\Interfaces\IEntity;

class BucketCollection implements IBucketCollection, IEntity {

	protected $id;

	protected $position;

	protected $limit;

	protected $entityId;

	protected $entityName;

	<<Id>>
	public function id($value = null)
	{
		if($value === null) return $this->id;
		$this->id = $value;
	}

    /**
     * Gets the value of id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the value of id.
     *
     * @param mixed $id the id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the value of position.
     *
     * @return mixed
     */
    <<Int>>
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Sets the value of position.
     *
     * @param mixed $position the position
     *
     * @return self
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Gets the value of limit.
     *
     * @return mixed
     */
    <<Int>>
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Sets the value of limit.
     *
     * @param mixed $limit the limit
     *
     * @return self
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Gets the value of entityId.
     *
     * @return mixed
     */
    <<ObjectId>>
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Sets the value of entityId.
     *
     * @param mixed $entityId the entity id
     *
     * @return self
     */
    protected function setEntityId($entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * Gets the value of entityName.
     *
     * @return mixed
     */
    <<String>>
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * Sets the value of entityName.
     *
     * @param mixed $entityName the entity name
     *
     * @return self
     */
    protected function setEntityName($entityName)
    {
        $this->entityName = $entityName;

        return $this;
    }

}
