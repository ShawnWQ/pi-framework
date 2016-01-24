<?hh


namespace Collaboration\ServiceInterface;



use Pi\Service;
use Collaboration\ServiceInterface\Data\PageRepository,
    Collaboration\ServiceInterface\Data\MeetingRepository;
use Collaboration\ServiceModel\PostPageRequest;
use Collaboration\ServiceModel\PostPageResponse;
use Collaboration\ServiceModel\GetPageRequest;
use Collaboration\ServiceModel\GetPageResponse;
use Collaboration\ServiceModel\Types\Page;
use Pi\Common\ClassUtils;


class PageService extends Service {

  public PageRepository $pageRepo;

  public MeetingRepository $meetingRepo;


  <<Request,Route('/page'),Method('POST')>>
  public function create(PostPageRequest $request)
  {
    $entity = new Page();
    ClassUtils::mapDto($request, $entity);
    $this->pageRepo->insert($entity);
  }

  <<Request,Route('/page/:slug'),Method('GET')>>
  public function get(GetPageRequest $request)
  {
    $response = new GetPageResponse();

    if(!is_null($request->getId())) {
      $response->setPage($this->getDtoById($request->getId()));
    } else if(!is_null($request->getSlug())) {
      $response->setPage($this->getDtoByName($request->getSlug()));
    } else {
      throw new \Exception('404');
    }

    return $response;
  }

  protected function getDtoById(\MongoId $id) : PageDto
  {
    return $this->pageRepo->getAs('Collaboration\ServiceModel\PageDto', $id);
  }

  protected function getDtoByName(string $name) : PageDto
  {
    return $this->pageRepo->queryBuilder('Collaboration\ServiceModel\PageDto')
      ->hydrate(true)
      ->field('name')->eq($name)
      ->getQuery()
      ->getSingleResult();
  }
}
