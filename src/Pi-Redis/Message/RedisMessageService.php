<?hh

namespace Pi\Redis\Message;

use Pi\Interfaces\IMessageService;

class RedisMessageService implements IMessageService {
	
  public function getStats()
  {

  }

  public function getStatsDescription()
  {

  }

  public function getStatus()
  {

  }

  public function registerHandler($handler)
  {

  }

  public function start()
  {

  }

  public function stop()
  {

  }

	/**
     * The factory for this Service
     * Use it to create producers and consumers
     */
  public function messageFactory() : IMessageFactory
  {
    return $this->factory;
  }

    /**
     * Shortcut to create a Message Queue client from the service message factory
     */
    public function createMessageQueueClient()
    {
      return $this->factory->createMessageQueueClient();
    }

    public function createMessageProducer()
    {
      return $this->factory->createMessageProducer();
    }

    private function createFactory($process, $processEx)
    {
      // create new message handler factory
      return new MessageHandlerFactory($this, $process, $processEx);
    }
  }