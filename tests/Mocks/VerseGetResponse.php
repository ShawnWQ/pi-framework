<?hh

namespace Mocks;

use Pi\Response;




class VerseGetResponse extends Response {

  public function jsonSerialize()
  {
    return get_object_vars($this);
  }

  protected $name = 'my-name';

  protected $id = 1;
}
