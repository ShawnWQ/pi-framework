<?hh

namespace Pi\ServiceModel\Types;
use Pi\Odm\Interfaces\IEntity;

<<Entity,Collection('run-plan')>>
class RunPlan implements IEntity {

  protected \MongoId $id;

  protected string $title;

  protected $user;

  protected ?\DateTime $startDate;

  protected string $address;

  protected $latitude;

  protected $longitude;

  protected string $description;

  <<EmbedOne('Pi\ServiceModel\Types\Author')>>
  public function getUser()
  {
    return $this->user;
  }

  public function setUser($author)
  {
    $this->user = $author;
  }

  <<Id>>
  public function id($value = null)
  {
    if($value === null) return $this->id;
    $this->id = $value;
  }

  <<String>>
  public function getTitle()
  {
    return $this->title;
  }

  public function setTitle(string $value)
  {
    $this->title = $value;
  }

  <<DateTime>>
  public function getStartDate()
  {
    return $this->startDate;
  }

  public function setStartDate(\DateTime $date)
  {
    $this->startDate = $datre;
  }

  <<String>>
  public function getAddress()
  {
    return $this->address;
  }

  public function setAddress(string $value)
  {
    $this->address = $value;
  }

  <<String>>
  public function getLatitude()
  {
    return $this->latitude;
  }

  public function setLatitude($value)
  {
    $this->latitude = $value;
  }

  <<String>>
  public function getLongitude()
  {
    return $this->longitude;
  }

  public function setLongitude($value)
  {
    $this->longitude = $value;
  }

  <<String>>
  public function getDescription()
  {
    return $this->description;
  }

  public function setDescription(string $value)
  {
    $this->description = $value;
  }
}
