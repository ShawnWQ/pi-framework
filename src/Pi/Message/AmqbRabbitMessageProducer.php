<?hh

namespace Pi\Message;
use Pi\Interfaces\IMessageProducer;
use Pi\Interfaces\IMessage;

class AmqbRabbitMessageProducer

  implements IMessageProducer {


    public function __construct(protected $mqChannel)
    {

    }
    public function publish($type, $messageBody)
    {

    }
    public function publishMessage($type, IMessage $message)
    {
      
    }
  }
