<?hh

namespace SpotEvents\ServiceModel;
use Pi\Response;

class GetNutritionSerieResponse extends Response {

  protected NutritionSerieDto $serie;

  public function getSerie() : NutritionSerieDto
  {
    return $this->article;
  }

  public function setSerie(NutritionSerieDto $dto) : void
  {
    $this->serie = $dto;
  }
}
