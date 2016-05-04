<?hh

namespace Warez\ServiceModel;

use Pi\Response;

class GetMoviesInfoResponse {

	protected array $movies;

	public function getMovies()
	{
		return $this->movies;
	}

	public function setMovies($movies)
	{
		$this->movies = $movies;
	}
}