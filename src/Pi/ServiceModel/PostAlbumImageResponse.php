<?hh

namespace Pi\ServiceModel;

use Pi\Response;

class PostAlbumImageResponse extends Response {

	protected $image;

	protected $feed;

	public function getImage()
	{
		return $this->image;
	}

	public function setImage($dto)
	{
		$this->image = $dto;
	}

	public function getFeed()
	{
		return $this->feed;
	}

	public function setFeed($feed)
	{
		$this->feed = $feed;
	}
}
