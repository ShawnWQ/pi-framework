<?hh

namespace SpotEvents\ServiceModel;

use Pi\Response;


class FindWorkoutResponse extends Response {

  protected $workouts;

  public function getWorkouts()
  {
    return $this->nutritions;
  }

  public function setWorkouts($workouts)
  {
    $this->workouts = $workouts;
  }
}
