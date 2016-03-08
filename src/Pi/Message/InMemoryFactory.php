<?hh

namespace Pi\Message;
use Pi\Interfaces\IMessageFactory;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer ;

class InMemoryFactory
  implements IMessageFactory, IContainable {

    public function __construct()
    {
        $this->clientFactory = new InMemoryMessageQueueClientFactory($this);
        $this->service = new InMemoryService($this);
    }

    public function getContext() : InMemoryContext
    {
      return $this->context;
    }

    public function ioc(IContainer $container)
    {
      $this->container = $container;
    }

    public function createMessageProducer()
    {
      return new InMemoryProducer($this->service);
    }

    /**
     * @return MessageHandler
     */
    public function createMessageQueueClient()
    {
      return $this->clientFactory->createMessageQueueClient();
    }

    protected $clientFactory;

    protected $context;

    protected $container;

    protected $service;
  }
