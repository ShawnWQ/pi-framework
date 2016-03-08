<?hh

namespace Warez\ServiceInterface;

use Pi\Service;
use Pi\PiFileMapper;
use Warez\ServiceModel\GetMoviesInfo;
use Warez\ServiceModel\GetMoviesInfoResponse;
use Warez\ServiceInterface\Data\MovieRepository;
use Warez\ServiceModel\PostMovieRequest;
use Warez\ServiceModel\PostMovieResponse;
use Warez\ServiceModel\GetMovieRequest;
use Warez\ServiceModel\GetMovieResponse;
use Warez\ServiceModel\GetMoviesRequest;
use Warez\ServiceModel\GetMoviesResponse;
use Warez\ServiceModel\PutMovieRequest;
use Warez\ServiceModel\PutMovieResponse;
use Warez\ServiceModel\MovieDto;
use Warez\ServiceModel\Types\Movie;
use Warez\ServiceModel\PostSrtToVttRequest;
use Warez\ServiceModel\PostSrtToVttResponse;
use Pi\Common\ClassUtils;
use Captioning\Format\SubripFile;
use Captioning\Format\WebvttFile;

class MovieService extends Service {

	public MovieRepository $movieRepo;

	<<Request,Route('/srt-to-vtt'),Method('POST')>>
	public function srtToVtt(PostSrtToVttRequest $request)
	{
		$response = new PostSrtToVttResponse();
		$srt = new SubripFile($request->getFilePath());
		$fileName = pathinfo($request->getFilePath(), PATHINFO_FILENAME);
		$path = pathinfo($request->getFilePath(), PATHINFO_DIRNAME);
    	$r = $srt->convertTo('webvtt')->save($path . '/' . $fileName . '.vtt');
    	return $response;
	}

	<<Request,Route('/movies'),Method('POST')>>
	public function post(PostMovieRequest $request)
	{
		$entity = new Movie();
		ClassUtils::mapDto($request, $entity);
		$this->movieRepo->insert($entity);
		$dto = new MovieDto();
		ClassUtils::mapDto($entity, $dto);

		$response = new PostMovieResponse();
		$response->setMovie($dto);
		return $response;
	}

	<<Request,Route('/movies/:id'),Method('POST')>>
	public function put(PutMovieRequest $request)
	{
		$this->movieRepo
			->queryBuilder()
			->update()
			->field('_id')->eq($request->getId())
			->field('title')->set($request->getTitle())
			->field('year')->set($request->getYear())
			->field('imdbId')->set($request->getImdbId())
			->field('movieUri')->set($request->getMovieUri())
			->field('coverSrc')->set($request->getCoverSrc())
			->field('captionSrc')->set($request->getCaptionSrc())
			->getQuery()
			->execute();

		$response = new PutMovieResponse();
		return $response;
	}

	<<Request,Route('/movies'),Method('GET')>>
	public function find(GetMoviesRequest $request)
	{
		$response = new GetMoviesResponse();

		$movies = $this->movieRepo
			->queryBuilder('Warez\ServiceModel\MovieDto')
			->find()
			->limit($request->getLimit())
			->skip($request->getSkip())
			->hydrate(true)
			->getQuery()
			->execute();

		$response->setMovies($movies);

		return $response;
	}

	<<Request,Route('/movies/:id'),Method('GET')>>
	public function get(GetMovieRequest $request)
	{
			$response = new GetMovieResponse();

			$movie = $this->movieRepo->getAs($request->getId(), 'Warez\ServiceModel\MovieDto');
			$response->setMovie($movie);

			return $response;
	}
	<<Request,Route('/movies-info'),Method('GET')>>
	public function search(GetMoviesInfo $request)
	{
		
		$mapper = new PiFileMapper(Set{$request->getDirPath()});
		$files = $mapper->getMapByExtension('mp4');
		$response = new GetMoviesInfoResponse();
		$response->setMovies($files);
		return $response;
	}
}
