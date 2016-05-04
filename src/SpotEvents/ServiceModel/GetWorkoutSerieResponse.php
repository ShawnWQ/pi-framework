<?hh

namespace SpotEvents\ServiceModel;
use Pi\Response;

class GetWorkoutSerieResponse extends Response {

  protected WorkoutSerieDto $serie;

  public function getSerie() : WorkoutSerieDto
  {
    return $this->article;
  }

  public function setSerie(WorkoutSerieDto $dto) : void
  {
    $this->serie = $dto;
  }
}
