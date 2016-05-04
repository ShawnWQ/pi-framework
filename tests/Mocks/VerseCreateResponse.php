<?hh

namespace Mocks;

class VerseCreateResponse
  implements \JsonSerializable{

    public function jsonSerialize()
    {
      return get_object_vars($this);
    }

    protected $message = 'Created dude, congragulations!';

    protected $date = '01/01/01';
}
