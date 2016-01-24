<?hh

namespace Mocks;

class VerseGetResponse
  implements \JsonSerializable{

  public function jsonSerialize()
  {
    return get_object_vars($this);
  }

  protected $name = 'my-name';

  protected $id = 1;
}
