<?hh

namespace Pi\AmqbRabbit;

use Pi\Interfaces\IMessageFactory;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AmqbRabbitFactory implements IMessageFactory{

    public function createMessageProducer()
    {
      $connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
      $channel = $connection->channel();

      $channel->queue_declare('hello', false, false, false, false);
      $msg = new AMQPMessage('Hello World!');
      $channel->basic_publish($msg, '', 'hello');

      $producer = new AmqbRabbitProducer($channel);
      return $producer;
    }

    public function createMessageQueueClient()
    {
      
    }
}
