<?hh

namespace Pi\ServiceModel;
use Pi\Odm\Interfaces\IEntity;

class PostRunPlanRequest  {

  protected string $title;

  protected ?\DateTime $startDate;

  protected string $address;

  protected $latitude;

  protected $longitude;

  protected string $description;

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
