<?hh

namespace Pi\ServiceModel;

use Pi\Response;


class GetAlbumImageResponse extends Response {

  protected AlbumImageDto $image;

  public function getImage() : AlbumImageDto
  {
    return $this->image;
  }

  public function setImage(AlbumImageDto $dto) : void
  {
    $this->image = $dto;
  }
}
