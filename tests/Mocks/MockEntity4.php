<?hh

namespace Mocks;
use Pi\Odm\Interfaces\IEntity;
use Pi\Odm\MongoEntity;

<<Entity>>
class MockEntity4 implements IEntity, \JsonSerializable {

  protected string $id;

  protected $name;

  protected $address;

  public function jsonSerialize()
  {
    return get_object_vars($this);
  }

  <<Id,String>>
  public function id($value = null)
  {
    if($value === null) return $this->id;
    $this->id = $value;
  }

   <<String,MinLength(400)>>
  public function name($value = null)
  {
      if($value === null) return $this->name;
      $this->name = $value;
  }

  <<String,MinLength(400),NotNull("default")>>
  public function address($value = null)
  {
      if($value === null) return $this->address;
      $this->address = $value;
  }
}
