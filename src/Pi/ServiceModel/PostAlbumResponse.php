<?hh

namespace Pi\ServiceModel;

use Pi\Response;

class PostAlbumResponse extends Response {

	protected $album;

	public function getAlbum()
	{
		return $this->album;
	}

	public function setAlbum($dto)
	{
		$this->album = $dto;
	}
}