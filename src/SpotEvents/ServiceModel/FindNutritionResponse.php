<?hh

namespace SpotEvents\ServiceModel;

use Pi\Response;


class FindNutritionResponse extends Response {

  protected $nutritions;

  public function getNutritions()
  {
    return $this->nutritions;
  }

  public function setNutritions($nutritions)
  {
    $this->nutritions = $nutritions;
  }
}
