<?hh

namespace Collaboration\ServiceModel;
use Pi\Response;

class GetPageResponse extends Response {

  protected PageDto $page;

  public function getPage()
  {
    return $this->page;
  }

  public function setPage(PageDto $dto)
  {
    $this->page = $dto;
  }
}
