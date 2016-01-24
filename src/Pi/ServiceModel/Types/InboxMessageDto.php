<?hh

namespace Pi\ServiceModel\Types;
use Pi\Odm\Interfaces\IEntity;

<<EmbeddedDocument>>
class InboxMessageDto implements \JsonSerializable, IEntity {

	protected \MongoId $fromId;

	protected \MongoId $toId;

	protected string $message;

	protected \DateTime $when;

	protected $received;

	public function jsonSerialize()
	{
		$arr = get_object_vars($this);
		$arr['fromId'] = (string)$arr['fromId'];
		$arr['toId'] = (string)$arr['toId'];
		return $arr;
	}

	public function received($value = null)
	{
		if($value === null) return $this->received;
		$this->received = $value;
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

	<<String>>
	public function message($message = null)
	{
		if($message === null) return $this->message;
		$this->message = $message;
	}

	<<DateTime>>
	public function when($date = null)
	{
		if($date === null) return $this->when;
		$this->when = $date;
	}
}