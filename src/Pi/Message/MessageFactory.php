<?hh

namespace Pi\Message;
use Pi\Interfaces\IMessage;

class MessageFactory {

  protected static $cacheFn = Set{};

  public static function createQueueClient()
  {

  }

  public static function create($response) : ?IMessage
  {
    if(is_null($response))
      return null;

    $type = get_class($response);
    if(self::$cacheFn->contains($type))
    {
      //return self::$cacheFn()->get($type)($response);
    }
    $message = new Message();
    $message->setBody($response);
    return $message;
    /*$fn = function($response){

    };

    self::$cacheFn[$type] = $fn;
    return $fn($response);
*/
  }
}
