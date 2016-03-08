<?hh

namespace Pi\ServiceModel\Types;

use Pi\Odm\Interfaces\IEntity;

class MessageBucket implements IEntity, \JsonSerializable {

	protected \MongoId $fromId;

	protected \MongoId $toId;

	protected $messages;

	protected \DateTime $when;

	protected \MongoId $id;

	public function jsonSerialize()
	{
		$var = get_object_vars($this);
		$var['id'] = (string)$var['_id'];
		$var['fromId'] = (string)$var['fromId'];
		$var['toId'] = (string)$var['toId'];
		return $var;
	}

	<<Id>>
	public function id($value = null)
	{
		if($value === null) return $this->id;
		$this->id = $value;
	}

	<<ObjectId>>
	public function fromId($value = null)
	{
		if($value === null) return $this->fromId;
		$this->fromId = $value;
	}

	<<ObjectId>>
	public function toId($value = null)
	{
		if($value === null) return $this->toId;
		$this->toId = $value;
	}

	<<EmbedMany('Pi\ServiceModel\Types\InboxMessage')>>
	public function messages($message = null)
	{
		if($message === null) return $this->messages;
		$this->messages = $message;
	}
}