<?hh

namespace SpotEvents\ServiceModel;

use Pi\Response;

class PostWorkoutResponse extends Response {

  protected WorkoutDto $workout;

  public function getWorkout() : WorkoutDto
  {
    return $this->workout;
  }

  public function setWorkout(WorkoutDto $dto) : void
  {
    $this->workout = $dto;
  }
}
