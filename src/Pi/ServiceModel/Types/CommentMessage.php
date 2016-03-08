<?hh

namespace Pi\ServiceModel\Types;

use Pi\Odm\Interfaces\IEntity;

<<EmbeddedDocument>>
class CommentMessage implements \JsonSerializable, IEntity {

	protected $author;

	protected string $message;

	protected \DateTime $when;

	protected \MongoId $id;

	public function jsonSerialize()
	{
		$arr = get_object_vars($this);
		if(array_key_exists('author', $arr) && is_array($arr['author'])) {
			if(array_key_exists('id', $arr['author'])) {
				$arr['author']['id'] = (string)$arr['author']['id'];
			}
			else if(array_key_exists('_id', $arr['author'])) {
				$arr['author']['id'] = (string)$arr['author']['_id'];
			}
		}
		return $arr;
	}

	<<Id>>
	public function id($value = null)
	{
		if($value === null) return $this->id;
		$this->id = $value;
	}

	<<Collection>>
	public function author($value = null)
	{
		if($value === null) return $this->author;
		$this->author = $value;
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
