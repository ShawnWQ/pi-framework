<?hh

namespace Mocks;
use Pi\Odm\Interfaces\IEntity;
use Pi\Odm\MongoEntity;

class MockEntity2Emb implements IEntity {
  protected $id;

  <<Id>>
  public function id($value = null)
  {
    if($value === null) return $this->id;
    $this->id = $value;
  }
}