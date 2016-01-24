<?hh

namespace Pi\ServiceModel;

use Pi\Response;

class GetAlbumResponse extends Response  {

	protected $images;

	public function getImages()
	{
		return $this->images;
	}

	public function setImages($images)
	{
		$this->images = $images;
	}
}