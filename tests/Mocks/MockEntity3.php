<?hh

namespace Mocks;
use Pi\Odm\Interfaces\IEntity;
use Pi\Odm\MongoEntity;

<<Entity>>
class MockEntity3 implements IEntity, \JsonSerializable {

  protected string $id;

  protected $name;

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
}
