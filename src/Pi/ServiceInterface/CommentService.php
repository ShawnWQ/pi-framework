<?hh

namespace Pi\ServiceInterface;
use Pi\Service;
use Pi\ServiceModel\GetCommentsRequest;
use Pi\ServiceModel\GetCommentsResponse;
use Pi\ServiceModel\PostCommentRequest;
use Pi\ServiceModel\PostCommentResponse;
use Pi\Odm\Interfaces\IRepository;
use Pi\ServiceModel\PostCommentArgs;
use Pi\ServiceInterface\Data\CommentRepository;
use Pi\ServiceModel\Types\FeedAction;
use Pi\ServiceInterface\UserFriendBusiness;
use Pi\ServiceInterface\UserFeedBusiness;

class CommentService extends Service {

  public CommentRepository $commentRepo;

  public UserFriendBusiness $friendBus;

  public UserFeedBusiness $feedBus;

  <<Request,Method('GET'),Route('/comment/:namespace/:entityId')>>
  public function getComments(GetCommentsRequest $request)
  {
    $response = $this->feedBusiness($request->getNamespace())->find($request);
    return $response;
  }

  <<Request,Method('POST'),Route('/comment/:namespace/:entityId'),Auth>>
  public function postComment(PostCommentRequest $request)
  {
    $response = new PostCommentResponse();
    $feedRes = $this->feedBusiness($request->getNamespace())->post($request, $this->request()->author());

    $args = new PostCommentArgs();
    $args->setNamespace($request->getNamespace());
    $args->setAuthor($this->request()->author());
    $args->setMessage($request->getMessage());
    $args->setEntityId($request->getEntityId());

    $this->eventManager()->dispatch('Pi\ServiceModel\PostCommentArgs', $args);
    $response->setComment($feedRes->getComment());
    return $response;
  }

  protected function feedBusiness(string $namespace)
  {
    return new FeedbackBusiness($this->commentRepo, $namespace);
  }
}
