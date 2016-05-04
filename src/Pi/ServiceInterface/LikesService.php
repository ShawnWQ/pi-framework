<?hh

namespace Pi\ServiceInterface;

use Pi\Service;
use Pi\ServiceModel\GetLikesRequest;
use Pi\ServiceModel\GetLikesResponse;
use Pi\ServiceModel\PostLikeRequest;
use Pi\ServiceModel\PostLikeResponse;


class LikesService extends Service {

  public LikesProvider $likesProv;

  <<Request,Method('GET'),Route('/like/:namespace/:entityId')>>
  public function getLikes(GetLikesRequest $request)
  {
    $ids = $this->likesProv->get($request->getEntityId());

    $response = new GetLikesResponse();
    $response->setLikes($ids);
    return $response;
  }

  <<Request,Method('POST'),Route('/like/:namespace/:entityId'),Auth>>
  public function postLike(PostLikeRequest $request)
  {
    $response = new PostLikeResponse();
    
    $this->likesProv->add($request->getEntityId(), $this->request()->getUserId(), $request->getNamespace());
    return $response;
  }
}
