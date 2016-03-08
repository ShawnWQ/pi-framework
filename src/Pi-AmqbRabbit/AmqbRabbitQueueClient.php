<?hh

namespace Pi\AmqbRabbit;

use Pi\Interfaces\IMessageQueueClient;

class AmqbRabbitQueueClient implements IMessageQueueClient {

  public function __construct(protected AmqbRabbitFactory $factory)
  {
    
  }

  //Acknowledge the message was received
  //public function ack();

  public function createMessage($type)
  {

  }

  public function get($type)
  {

  }

  public function getAsync($type)
  {

  }

  //public function nak();

  //public function notify();

  //public function publish();
}
