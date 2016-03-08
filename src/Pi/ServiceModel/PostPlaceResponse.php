<?hh

namespace Pi\ServiceModel;

use Pi\Response;

class PostPlaceResponse extends Response {

    protected $place;

    public function getPlace()
    {
      return $this->place;
    }

    public function setPlace(PlaceDto $dto)
    {
      $this->place = $dto;
    }
}
