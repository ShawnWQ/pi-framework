<?hh

namespace Pi\ServiceModel\Types;

use Pi\Odm\Interfaces\IEntity;

<<Entity>>
class Author implements \JsonSerializable, IEntity {

	protected $id;

	protected $displayName;

    public function jsonSerialize()
    {
        $arr = get_object_vars($this);
        $arr['id'] = (string)$arr['id'];
        return $arr;
    }

    <<Id,ObjectId>>
    public function id($value = null)
    {
        if($value === null) return $this->id;
        $this->id = $value;
    }

    <<String>>
    public function displayName()
    {
        return $this->displayName;
    }

    /**
     * Gets the value of id.
     *
     * @return mixed
     */
    <<String>>
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
     * Gets the value of displayName.
     *
     * @return mixed
     */
    <<String>>
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Sets the value of displayName.
     *
     * @param mixed $displayName the display name
     *
     * @return self
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }
}