<?hh

namespace Pi\AmqbRabbit;

use Pi\Interfaces\IMessageQueueClientFactory;

class AmqbRabbitQueueClientFactory implements IMessageQueueClientFactory {

    public function __construct(protected AmqbRabbitFactory $factory)
    {

    }
    public function createMessageQueueClient()
    {
      return new AmqbRabbitQueueClient($this->factory);
    }
}
