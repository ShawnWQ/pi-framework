<?hh

namespace Pi\Message;
use Pi\Interfaces\IMessageProducer;
use Pi\Interfaces\IMessage;

class RedisMessageProducer implements IMessageProducer {

  public function __construct(protected RedisMessageService $service)
  {
  }

  public function publish($type, $messageBody)
  {
    return $this->service->publish($messageBody);

  }
  public function publishMessage($type, IMessage $message)
  {

  }
}
