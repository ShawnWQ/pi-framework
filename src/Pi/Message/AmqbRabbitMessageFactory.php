<?hh

namespace Pi\Message;
use Pi\Interfaces\IMessageFactory;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AmqbRabbitMessageFactory
  implements IMessageFactory{


    public function createMessageProducer()
    {
      $connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
      $channel = $connection->channel();

      $channel->queue_declare('hello', false, false, false, false);
      $msg = new AMQPMessage('Hello World!');
      $channel->basic_publish($msg, '', 'hello');

      $producer = new AmqbRabbitMessageProducer($channel);
      return $producer;
    }
}
