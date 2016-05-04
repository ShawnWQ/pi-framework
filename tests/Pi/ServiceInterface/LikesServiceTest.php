<?hh

use Pi\ServiceModel\PostLikeRequest;
use Pi\ServiceModel\PostLikeResponse;
use Pi\ServiceModel\GetLikesRequest;
use Pi\ServiceModel\GetLikesResponse;
use Mocks\BibleHost;
use Mocks\MockHostProvider;


class LikesServiceTest extends \PHPUnit_Framework_TestCase {

  protected $host;

  public function setUp()
  {
    $this->host = new BibleHost();
    $this->host->init();
  }

  public function testCanLike()
  {
    $entity = new \MongoId();
    $request = new PostLikeRequest();
    $request->setNamespace('test');
    $request->setEntityId($entity);

    $response = MockHostProvider::execute($request);
    if(!$response instanceof PostLikeResponse) {
      throw new \Exception('excepted PostLikeResponse');
    }

    $req = new GetLikesRequest();
    $req->setEntityId($entity);
    $req->setNamespace('test');

    $res = MockHostProvider::execute($req);

    if(!$res instanceof GetLikesResponse) {
      throw new \Exception('excepted GetLikesResponse');
    }

    $this->assertTrue(count($res->getLikes()) === 1);
  }

}
