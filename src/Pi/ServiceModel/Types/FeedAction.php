<?hh

namespace Pi\ServiceModel\Types;
use Pi\Odm\Interfaces\IEntity;

<<Entity>>
class FeedAction implements IEntity, \JsonSerializable {

  protected \MongoId $id;

  protected $author;

  public function __construct(
    protected ?\MongoId $actorId = null,
    protected ?\DateTime $conducted = null,
    protected ?bool $isHidden = null,
    protected ?string $scope = null,
    protected $fanOut = null,
    protected ?array $descriptor = null,
    protected ?string $feedType = null,
  )
  {

  }

  public function jsonSerialize()
  {
    $vars = get_object_vars($this);
    $vars['id'] = (string)$vars['id'];
    $vars['actorId'] = (string)$vars['actorId'];
    return $vars;
  }

  <<Id>>
  public function id($id = null)
  {
    if($id === null) return $this->id;
    $this->id = $id;
  }

  <<String>>
  public function getFeedType()
  {
    return $this->feedType;
  }

  <<ObjectId>>
  public function getActorId()
  {
    return $this->actorId;
  }

  <<DateTime>>
  public function getConducted()
  {
    return $this->conducted;
  }

  <<String>>
  public function getIsHidden()
  {
    return $this->isHidden;
  }

  <<String>>
  public function getScope()
  {
    return $this->scope;
  }

  <<String>>
  public function getFanOut()
  {
    return $this->fanOut;
  }

  <<Collection>>
  public function getDescriptor()
  {
    return $this->descriptor;
  }

  public function setAuthor($value)
  {
    $this->author = $value;
  }

  <<EmbedOne('Pi\ServiceModel\Types\Author')>>
  public function getAuthor()
  {
    return $this->author;
  }
}
