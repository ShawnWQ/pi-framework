<?hh

namespace Pi\Realtime\ServicesInterface;
use Pi\Service;

class ChatService extends Service {

  <<RedisManager>>
  public $redis;

  <<MongoConnection>>
  public $dm;


  public function connect()
  {

  }

  public function disconnect()
  {

  }

  /**
   * Send a message to a specific user
   * @return [type] [description]
   */
  public function sendUser()
  {

  }

  /**
   * Send a message to a specific group
   */
  public function sendGroup()
  {

  }
}
