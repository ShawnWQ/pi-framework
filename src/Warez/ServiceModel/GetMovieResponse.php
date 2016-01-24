<?hh

namespace Warez\ServiceModel;

use Pi\Response;

class GetMovieResponse extends Response  {
  protected $movie;

  public function getMovie()
  {
    return $this->movie;
  }

  public function setMovie($dto)
  {
    $this->movie = $dto;
  }
}
