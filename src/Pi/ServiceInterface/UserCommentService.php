<?hh

namespace Pi\ServiceInterface;

use Pi\Interfaces\ICommentRepository;
use Pi\ServiceModel\PostCommentRequest;
use Pi\ServiceModel\PostCommentResponse;
use Pi\ServiceModel\GetCommentsRequest;
use Pi\ServiceModel\GetCommentsResponse;
use Pi\ServiceModel\Types\CommentEntity;
use Pi\ServiceModel\CommentDto;
use Pi\Common\ClassUtils;


class UserCommentService extends FeedBackBusiness {

  public function __construct()
  {
      parent::__construct(new UserCommentRepository());
  }
  <<Request,Route('/user/:entityId/comments'),Method('GET')>>
  public function find(GetCommentsRequest $request)
  {
    return parent::find($request);
  }

  <<Request,Route('/user/:entityId/comments'),Method('POST')>>
  public function post(PostCommentRequest $request)
  {
    return parent::post($request);
  }
}
