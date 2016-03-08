<?hh

namespace Pi\Odm;
use Pi\Odm\Interfaces\IEntity;

abstract class MongoEntity
  implements \JsonSerializable, IEntity{


  public function jsonSerialize()
  {
    return get_object_vars($this);
  }

  <<Id>>
  public function id($id = null)
  {
    if(is_null($id)) return $this->id;
    $this->id = $id;
  }

  protected $id;
}
