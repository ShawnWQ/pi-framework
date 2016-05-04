<?hh

namespace Pi\ServiceModel\Types;

use Pi\Odm\Interfaces\IEntity;

<<MultiTenant,Entity,Collection('appFeed')>>
class AppFeed extends AbstractFeed implements IEntity, \JsonSerializable {
	protected $appId;

	protected FeedType $type;

	protected string $text;

	protected \MongoId $id;

	protected $data;

	public function jsonSerialize()
	{
		return get_object_vars($this);
	}

    /**
     * Gets the value of data.
     *
     * @return mixed
     */
    <<EmbedMany('Pi\ServiceModel\Types\AppFeed')>>
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets the value of data.
     *
     * @param mixed $data the data
     *
     * @return self
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

	<<Id>>
	public function id($id = null)
	{
		if($id === null) return $this->id;
		$this->id = $id;
	}

	public function setId(\MongoId $id)
	{
		$this->id = $id;
	}

	public function getText()
	{
		return $this->text;
	}

	public function setText(string $value)
	{
		$this->text = $value;
	}

	public function getType()
	{
		return $this->type;
	}

	public function setType($value)
	{
		$this->type = $value;
	}
}