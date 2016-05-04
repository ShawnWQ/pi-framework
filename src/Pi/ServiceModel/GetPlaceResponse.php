<?hh

namespace Pi\ServiceModel;

use Pi\Response;


class GetPlaceResponse extends Response {

  protected PlaceDto $place;

  public function getPlace() : PlaceDto
  {
    return $this->place;
  }

  public function setPlace(PlaceDto $dto) : void
  {
    $this->place = $dto;
  }
}
