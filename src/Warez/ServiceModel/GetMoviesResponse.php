<?hh

namespace Warez\ServiceModel;

use Pi\Response;

class GetMoviesResponse extends Response {

  protected $movies;

  public function getMovies()
  {
    return $this->movies;
  }

  public function setMovies($movies)
  {
    $this->movies = $movies;
  }
}
