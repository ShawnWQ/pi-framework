<?hh

namespace SpotEvents\ServiceModel;

use Pi\Response;

class PostNutritionResponse extends Response {

  protected NutritionDto $nutrition;

  public function getNutrition() : NutritionDto
  {
    return $this->nutrition;
  }

  public function setNutrition(NutritionDto $dto) : void
  {
    $this->nutrition = $dto;
  }
}
