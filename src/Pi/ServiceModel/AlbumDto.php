<?hh

namespace Pi\ServiceModel;

use Pi\Odm\Interfaces\IEntity;

<<Entity,Collection("album")>>
class AlbumDto implements \JsonSerializable, IEntity {

	protected string $title;

	protected \MongoId $id;

	protected \MongoId $authorId;

	protected array $lastImages;

	<<Id>>
	public function id($value = null)
	{
		if($value === null) return $this->id;
		$this->id = $value;
	}

	<<Collection>>
	public function getLastImages()
	{
		return $this->lastImages;
	}

	public function setLastImages(array $values)
	{
		$this->lastImages = $values;
	}

	<<ObjectId>>
	public function getAuthorId()
	{
		return $this->authorId;
	}

	public function setAuthorId(\MongoId $id) : void
	{
		$this->authorId = $id;
	}

	public function jsonSerialize()
	{
		$a = get_object_vars($this);
		$a['id'] = (string)$a['id'];
		return $a;
	}

	<<String>>
	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle(string $text)
	{
		$this->title = $text;
	}
}
