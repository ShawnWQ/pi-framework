<?hh

use Warez\ServiceModel\GetMoviesInfo;
use Warez\ServiceModel\GetMovieRequest;
use Warez\ServiceModel\GetMoviesRequest;
use Warez\ServiceModel\GetMoviesResponse;
use Warez\ServiceModel\GetMoviesInfoResponse;
use Warez\ServiceModel\PostMovieRequest;
use Warez\ServiceModel\PutMovieRequest;
use Mocks\MockHostProvider;
use Pi\Common\ClassUtils;

class MovieServiceTest extends \PHPUnit_Framework_TestCase {

	protected $movieRepo;

	public function testCanCreateMovie()
	{
		$request = new PostMovieRequest();
		$request->setTitle('random movie');
		$res = MockHostProvider::executeWarez($request);
		$this->assertEquals($res->getMovie()->getTitle(), $request->getTitle());
	}

	public function testCanSaveMovie()
	{
		$request = new PostMovieRequest();
		$request->setTitle('random movie');
		$res = MockHostProvider::executeWarez($request);

		$req = new PutMovieRequest();
		ClassUtils::mapDto($res->getMovie(), $req);
		$req->setTitle('other');
		$response = MockHostProvider::executeWarez($req);

		$request = new GetMovieRequest();
		$request->setId($res->getMovie()->id());
		$res = MockHostProvider::executeWarez($request);

		$this->assertEquals($req->getTitle(), $res->getMovie()->getTitle());


	}

	public function testCanFindMovies()
	{
		$req = new GetMoviesRequest();
		$res = MockHostProvider::executeWarez($req);
		$this->assertTrue(count($res->getMovies()) > 0);

	}

	public function testCanGetAllMovies()
	{
		$req = new GetMoviesInfo();
		$req->setDirPath(__DIR__ . '/../files/');

		$res = MockHostProvider::executeWarez($req);
		$this->assertTrue($res instanceof GetMoviesInfoResponse);
		$this->assertTrue($res->getMovies()->count() === 1);
	}

}
