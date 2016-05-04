<?hh

namespace Pi\ServiceModel;

class PostAlbumImage {

	protected string $imageSrc;

	protected string $text;

	protected \MongoId $albumId;

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