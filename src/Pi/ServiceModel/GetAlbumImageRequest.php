<?hh

namespace Pi\ServiceModel;

class GetAlbumImageRequest {

  protected \MongoId $imageId;

  <<ObjectId>>
  public function getImageId() : \MongoId
  {
    return $this->imageId;
  }

  public function setImageId(\MongoId $id) : void
  {
    $this->imageId = $id;
  }

}
