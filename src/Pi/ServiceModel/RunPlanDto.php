<?hh

namespace Pi\ServiceModel;
use Pi\Odm\Interfaces\IEntity;

<<Entity,Collection('run-plan')>>
class RunPlanDto implements IEntity, \JsonSerializable {

  protected \MongoId $id;

  protected string $title;

  protected $user;

  protected ?\DateTime $startDate;

  protected string $address;

  protected $latitude;

  protected $longitude;

  protected string $description;

  public function jsonSerialize()
  {
    $vars = get_object_vars($this);
    $vars['id'] = (string)$vars['id'];
    if(array_key_exists('user', $vars) && is_array($vars['user']))
      $vars['user']['id'] = (string)$vars['user']['_id'];
      
    return $vars;
  }

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
