<?hh

namespace Pi\Message;
use Pi\Interfaces\IMessageQueueClient;


class InMemoryMessageQueueClient
  implements IMessageQueueClient{

  public function __construct(protected InMemoryProducer $producer)
  {

  }
  public function createMessage($type)
  {
    return MessageFactory::create($type);
  }

  public function publish($request)
  {
    $this->producer->publish(get_class($request), $request);
  }

  public function get($type)
  {

  }

  public function subscribe($message, $listener){


  }

  public function getAsync($type)
  {

  }
}
