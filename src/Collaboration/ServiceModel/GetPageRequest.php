<?hh

namespace Collaboration\ServiceModel;

class GetPageRequest {

  protected ?string $slug;

  protected ?\MongoId $id;

  <<String,Nullable>>
  public function getSlug() : ?string
  {
    return $this->slug;
  }

  public function setSlug(string $slug) : void
  {
    $this->slug = $slug;
  }

  <<ObjectId,Nullable>>
  public function getId()
  {
    return $this->id;
  }

  public function setId(\MongoId $id) : void
  {
    $this->id = $id;
  }
}
