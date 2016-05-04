<?hh

namespace Pi\ServiceModel;

class GetAlbumRequest {

	protected \MongoId $albumId;

	<<ObjectId>>
	public function getAlbumId()
	{
		return $this->albumId;
	}

	public function setAlbumId(\MongoId $id)
	{
		$this->albumId = $id;
	}
}
