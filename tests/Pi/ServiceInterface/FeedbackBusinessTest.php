<?hh
use Pi\ServiceInterface\FeedbackBusiness;
use Pi\ServiceInterface\Data\CommentRepository;
use Pi\ServiceModel\PostCommentRequest;
use Pi\ServiceModel\PostCommentResponse;
use Pi\ServiceModel\GetCommentsRequest;
use Pi\ServiceModel\GetCommentsResponse;
use Mocks\OdmContainer;
use Mocks\AuthMock;
use Mocks\BibleHost;

class FeedbackBusinessTest extends \PHPUnit_Framework_TestCase {

  protected $host;

  public function testRepository()
  {
    $this->host = new BibleHost();
    AuthMock::mock();
    $this->host->init();
    $repo = $this->host->container()->get('Pi\ServiceInterface\Data\CommentRepository');
    $business = new FeedbackBusiness($repo, 'test');
    $req = new PostCommentRequest();
    $req->setEntityId(new \MongoId());
    $req->setMessage('test');

    $res = $business->post($req, $this->host->container()->get('IRequest')->author());

    $reqGet = new GetCommentsRequest();
    $reqGet->setEntityId($req->getEntityId());


  }
}
