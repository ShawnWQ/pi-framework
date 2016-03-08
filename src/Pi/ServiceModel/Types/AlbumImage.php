<?hh

namespace Pi\ServiceModel\Types;

use Pi\Odm\Interfaces\IEntity;

<<Entity,Collection("album-image")>>
class AlbumImage implements \JsonSerializable, IEntity {

	protected string $imageSrc;

	protected string $text;

	protected \MongoId $albumId;

	protected \MongoId $id;

	public function jsonSerialize()
	{
		$a = get_object_vars($this);
		$a['id'] = (string)$a['id'];
		return $a;
	}
	
	<<Id>>
	public function id($value = null)
	{
		if($value === null) return $this->id;
		$this->id = $value;
	}

	<<String>>
	public function getImageSrc()
	{
		return $this->imageSrc;
	}

	public function setImageSrc(string $src)
	{
		$this->imageSrc = $src;
	}

	<<String>>
	public function getText()
	{
		return $this->text;
	}

	public function setText(string $text)
	{
		$this->text = $text;
	}

	<<ObjectId>>
	public function getAlbumId()
	{
		return  $this->albumId;
	}

	public function setAlbumId(\MongoId $id)
	{
		$this->albumId = $id;
	}
}