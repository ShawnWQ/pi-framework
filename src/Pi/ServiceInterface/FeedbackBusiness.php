<?hh

namespace Pi\ServiceInterface;

use Pi\Interfaces\ICommentRepository;
use Pi\ServiceModel\PostCommentRequest;
use Pi\ServiceModel\PostCommentResponse;
use Pi\ServiceModel\GetCommentsRequest;
use Pi\ServiceModel\GetCommentsResponse;
use Pi\ServiceModel\Types\CommentBucket;
use Pi\ServiceModel\Types\CommentMessage;
use Pi\Common\ClassUtils;
use Pi\Host\HostProvider;

class FeedbackBusiness {

  protected $entityRepo;

  public function __construct(public ICommentRepository $commentRepo, protected string $namespace)
  {
    $this->entityRepo = HostProvider::instance()->container()->getRepositoryByNamespace($namespace);

  }

  public function find(GetCommentsRequest $request, $author = null)
  {
    $response = new GetCommentsResponse();
    $data = $this->commentRepo->get($request->getEntityId());

    $response->setComments($data);
    return $response;
  }

  public function post(PostCommentRequest $request, $author)
  {

    $response = new PostCommentResponse();

    $entity = new CommentMessage();
    ClassUtils::mapDto($request, $entity);

    $entity->author($author);
    $entity->when(new \DateTime('now'));
    $repo = $this->entityRepo;

    $this->commentRepo->add($request->getEntityId(), $entity, 'entityId', $repo);

    $response->setComment($entity);

    return $response;
  }

}
