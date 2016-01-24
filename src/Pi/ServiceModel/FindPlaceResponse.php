<?hh

namespace Pi\ServiceModel;
use Pi\Response;
class FindPlaceResponse extends Response {

  protected $places;

  public function getPlaces()
  {
    return $this->places;
  }

  public function setPlaces($places)
  {
    $this->places = $places;
  }
}
