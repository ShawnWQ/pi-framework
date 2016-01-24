<?hh

namespace Pi\Message;
use Pi\Interfaces\IMessageQueueClientFactory;

class InMemoryMessageQueueClientFactory
  implements IMessageQueueClientFactory {

    public function __construct(protected InMemoryFactory $factory)
    {

    }
    public function createMessageQueueClient()
    {
      return new InMemoryMessageQueueClient($this->factory->createMessageProducer());
    }
}
